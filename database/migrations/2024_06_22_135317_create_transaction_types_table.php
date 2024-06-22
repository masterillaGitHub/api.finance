<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        if (config('database.add_initial_migration_data')) {
            $this->addInitialData();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_types');
    }

    private function addInitialData(): void
    {
        DB::table('transaction_types')->insert([
            ['name' => 'Витрата'],
            ['name' => 'Дохід'],
            ['name' => 'Переказ'],
        ]);
    }
};
