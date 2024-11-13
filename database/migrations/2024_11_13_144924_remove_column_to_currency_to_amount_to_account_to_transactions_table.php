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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['to_account_id']);
            $table->dropForeign(['to_currency_id']);

            $table->dropColumn('to_account_id');
            $table->dropColumn('to_currency_id');
            $table->dropColumn('to_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('to_account_id')
                ->nullable()
                ->after('transfer_transaction_id')
                ->constrained('accounts');
            $table->foreignId('to_currency_id')
                ->nullable()
                ->after('to_account_id')
                ->constrained('currencies');
            $table->bigInteger('to_amount')
                ->nullable()
                ->after('to_currency_id');
        });
    }
};
