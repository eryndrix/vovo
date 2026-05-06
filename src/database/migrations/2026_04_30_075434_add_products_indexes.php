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
            CREATE INDEX "products_name_trgm_idx"
            ON "ovov_products"
            USING GIN ("name" gin_trgm_ops)
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            CREATE INDEX "idx_products_category_rating"
            ON "ovov_products" ("category_id", "rating" DESC)
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            CREATE INDEX "idx_products_price"
            ON "ovov_products" ("price")
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            CREATE OR REPLACE FUNCTION public.f_unaccent(input text)
            RETURNS text AS
            $func$
                SELECT public.unaccent($1::text);
            $func$ LANGUAGE sql IMMUTABLE;
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            CREATE INDEX "idx_products_name_unaccent_lower"
            ON "ovov_products"
            USING GIN (public.f_unaccent(lower("name")) gin_trgm_ops);
        SQL));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement(query: rtrim(string: <<<'SQL'
            DROP INDEX IF EXISTS "products_name_trgm_idx"
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            DROP INDEX IF EXISTS "idx_products_category_rating"
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            DROP INDEX IF EXISTS "idx_products_price"
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            DROP INDEX IF EXISTS "idx_products_name_unaccent_lower"
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            DROP FUNCTION IF EXISTS public.f_unaccent(text)
        SQL));
    }
};
