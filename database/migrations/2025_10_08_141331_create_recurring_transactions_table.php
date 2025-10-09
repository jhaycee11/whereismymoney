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
        Schema::create('recurring_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['expense', 'income']);
            $table->string('category'); // Category for expenses or Source for income
            $table->integer('amount');
            $table->integer('day_of_month'); // Day to execute (1-31)
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('last_executed_at')->nullable(); // Track when last executed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_transactions');
    }
};
