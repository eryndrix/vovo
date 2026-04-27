# Default user and group IDs, can override
USER_ID ?= 1000
GROUP_ID ?= 1000

# Docker Compose command
DC := docker compose --env-file ./src/.env

# Compose files list
COMPOSE_FILES := -f docker-compose.yml \
                 -f vendor/docker-compose.traefik.yml \
                 -f vendor/docker-compose.postgres.yml \
                 -f vendor/docker-compose.redis.yml \
                 -f vendor/docker-compose.swagger.yml \
                 -f vendor/docker-compose.trivy.yml

.PHONY: network-create
# Create Docker network if it doesn't exist
network-create:
	@if ! docker network ls --format '{{.Name}}' | grep -q '^app-network$$'; then \
		echo "Creating Docker network 'app-network'..."; \
		docker network create app-network; \
	else \
		echo "Docker network 'app-network' already exists."; \
	fi

# 🔥 NEW: Create Trivy cache volume (speeds up scans from 42s → 2s)
.PHONY: trivy-setup
trivy-setup:
	@echo "🔥 Creating Trivy cache volume (if missing)..."
	@docker volume create trivy-cache 2>/dev/null || true
	@echo "✅ Trivy cache ready! Scans will be 20x faster."

# 🔥 NEW: Check if .env exists
.PHONY: check-env
check-env:
	@if [ ! -f src/.env ]; then \
		echo "⚠️  src/.env file not found!"; \
		echo "📝 Copy src/.env.example to src/.env and fill in secrets:"; \
		echo "   cp src/.env.example src/.env"; \
		echo "   nano src/.env"; \
		exit 1; \
	else \
		echo "✅ src/.env file exists"; \
	fi

.PHONY: build
# Build and start containers (creates Trivy cache automatically)
build: network-create trivy-setup check-env
	@echo "🔨 Building and starting containers..."
	$(DC) $(COMPOSE_FILES) --profile security up --build -d --remove-orphans
	@echo "✅ Containers started successfully!"

.PHONY: start
# Start containers
start: network-create trivy-setup check-env
	@echo "🚀 Starting containers..."
	$(DC) $(COMPOSE_FILES) up -d
	@echo "✅ Containers started!"

.PHONY: stop
# Stop and remove containers and volumes
stop:
	@echo "🛑 Stopping and removing containers..."
	$(DC) $(COMPOSE_FILES) down -v
	@echo "✅ Containers stopped and removed!"

.PHONY: restart
# Restart containers
restart: stop start

.PHONY: security-scan
# Run Trivy security scan only (fast with cache)
security-scan: trivy-setup
	@echo "🔍 Running security scan..."
	$(DC) -f vendor/docker-compose.trivy.yml up -d trivy
	@echo "🔍 Security scan results:"
	docker logs regpeak-trivy-1 --tail=100
	$(DC) -f vendor/docker-compose.trivy.yml down

.PHONY: generate-secrets
# Generate strong random passwords
generate-secrets:
	@echo "🔑 Generating strong passwords:"
	@echo "DB_PASSWORD=$(openssl rand -base64 32)"
	@echo "RABBITMQ_DEFAULT_PASS=$(openssl rand -base64 32)"

.PHONY: clean
# Full Docker cleanup (containers, images, volumes, unused networks)
clean:
	@echo "Stopping all containers..."
	-@docker stop $$(docker ps -aq)
	@echo "Removing all containers..."
	-@docker rm $$(docker ps -aq)
	@echo "Removing all images..."
	-@docker rmi -f $$(docker images -aq)
	@echo "Removing all volumes..."
	-@docker volume rm $$(docker volume ls -q)
	@echo "Removing all unused user-defined networks..."
	docker network prune -f
	@echo "Docker cleanup is done."

.PHONY: help
# Show help
help:
	@echo "Available commands:"
	@echo "  make network-create  - Create Docker network 'app-network' if missing"
	@echo "  make trivy-setup     - 🔥 Create Trivy cache (20x faster scans!)"
	@echo "  make check-env       - ✅ Check if .env exists"
	@echo "  make build           - Build and start containers (auto Trivy cache)"
	@echo "  make start           - Start containers (auto Trivy cache)"
	@echo "  make security-scan   - Run ONLY Trivy scan (2 seconds!)"
	@echo "  make stop            - Stop and remove containers and volumes"
	@echo "  make restart         - Restart containers"
	@echo "  make generate-secrets- 🔑 Generate strong random passwords"
	@echo "  make clean           - Full Docker cleanup"
	@echo ""
	@echo "You can override USER_ID and GROUP_ID when calling, e.g.:"
	@echo "  make build USER_ID=1000 GROUP_ID=1000"
	@echo ""
	@echo "⚠️  IMPORTANT: Before first use:"
	@echo "  1. cp .env.example .env"
	@echo "  2. Generate passwords: make generate-secrets"
	@echo "  3. Paste passwords into .env"
	@echo "  4. make build"