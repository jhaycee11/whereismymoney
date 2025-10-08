<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop description column from expenses table
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('description');
        });

        // Drop description column from incomes table
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back description column to expenses table
        Schema::table('expenses', function (Blueprint $table) {
            $table->string('description')->after('category');
        });

        // Add back description column to incomes table
        Schema::table('incomes', function (Blueprint $table) {
            $table->string('description', 500)->after('source');
        });
    }
};
