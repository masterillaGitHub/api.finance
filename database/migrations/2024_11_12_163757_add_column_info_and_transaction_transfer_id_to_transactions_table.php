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
            $table->json('info')
                ->nullable()
                ->after('note');
            $table->foreignId('transfer_transaction_id')
                ->nullable()
                ->after('info')
                ->constrained('transactions');
            $table->string('id_origin', 100)
                ->after('transfer_transaction_id')
                ->nullable()
                ->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['transfer_transaction_id']);
            $table->dropColumn('transfer_transaction_id');
            $table->dropColumn('info');
            $table->dropColumn('id_origin');
        });
    }
};
