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
            ALTER TABLE "ovov_products"
            ENABLE ROW LEVEL SECURITY
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            CREATE POLICY "products_access"
            ON "ovov_products"
            FOR SELECT
            USING (true)
        SQL));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement(query: rtrim(string: <<<'SQL'
            DROP POLICY IF EXISTS "products_access"
            ON "ovov_products"
        SQL));

        DB::statement(query: rtrim(string: <<<'SQL'
            ALTER TABLE "ovov_products"
            DISABLE ROW LEVEL SECURITY
        SQL));
    }
};
