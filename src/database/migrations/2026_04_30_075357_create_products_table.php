<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'products',
            callback: function (Blueprint $table): void {
                $table->uuid(column: 'id')->primary()->default(
                    DB::raw(value: 'uuidv7()')
                );

                $table->string(column: 'name', length: 80);

                $table->unsignedBigInteger(column: 'price');
                $table->float(column: 'rating')->default(value: 0);

                $table->boolean(
                    column: 'in_stock')->default(value: true);
                $table->uuid(column: 'category_id');

                $table->timestamp(
                    column: 'created_at',
                    precision: 6
                )->useCurrent();
                
                $table->timestamp(
                    column: 'updated_at',
                    precision: 6
                )->useCurrent();
            }
        );

        Schema::table(table: 'products',
            callback: function (Blueprint $table): void {
                $table->comment(comment: 'Продукты');

                $table->foreign(columns: 'category_id')
                    ->references(columns: 'id')
                    ->on(table: 'categories')
                    ->cascadeOnDelete();

                $table->index(columns: 'created_at');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'products');
    }
};