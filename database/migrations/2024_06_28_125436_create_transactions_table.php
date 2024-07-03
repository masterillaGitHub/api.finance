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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('type_id')->constrained('transaction_types');
            $table->foreignId('account_id')->constrained();
            $table->foreignId('category_id')->constrained('transaction_categories');
            $table->foreignId('currency_id')->constrained();
            $table->foreignId('transfer_transaction_id')->nullable()->constrained('transactions');
            $table->enum('input_type', ['auto', 'manual'])->default('manual');
            $table->bigInteger('amount');
            $table->string('note', 400)->nullable();
            $table->timestamp('transaction_at');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
