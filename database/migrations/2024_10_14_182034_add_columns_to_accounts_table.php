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
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('bank_id');
            $table->dropColumn('bank_name');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->foreignId('bank_id')
                ->after('credit_limit')
                ->nullable()
                ->constrained();
            $table->string('id_origin', 100)
                ->after('bank_id')
                ->unique()
                ->nullable();
            $table->json('data_origin')
                ->after('id_origin')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropForeign(['bank_id']);
            $table->dropColumn('bank_id');
            $table->dropColumn('id_origin');
            $table->dropColumn('data_origin');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->string('bank_id', 100)->nullable()->after('icon');
            $table->string('bank_name', 100)->nullable()->after('bank_id');

        });
    }
};
