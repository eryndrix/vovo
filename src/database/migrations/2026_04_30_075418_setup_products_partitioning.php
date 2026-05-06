<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(query: rtrim(string: <<<'SQL'
            DO $$
            DECLARE
                "pk_name" TEXT;
            BEGIN
                SELECT "conname" INTO "pk_name"
                FROM "pg_constraint"
                WHERE "conrelid" = '"ovov_products"'::regclass
                AND "contype" = 'p';

                IF "pk_name" IS NOT NULL THEN
                    EXECUTE 'ALTER TABLE "ovov_products" DROP CONSTRAINT "'
                        || "pk_name"
                        || '" CASCADE';
                END IF;
            END $$;
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            ALTER TABLE "ovov_products"
            ADD PRIMARY KEY ("id", "created_at")
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            SELECT create_hypertable(
                'ovov_products',
                'created_at',
                chunk_time_interval => INTERVAL '1 month',
                if_not_exists => TRUE
            )
        SQL));
    }
};
