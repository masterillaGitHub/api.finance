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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('category_id')->constrained('account_categories');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->enum('input_type', ['auto', 'manual'])->default('manual');
            $table->string('name', 100);
            $table->string('icon', 100);
            $table->string('bank_id', 100)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->unsignedInteger('credit_limit')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
