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
        Schema::create('transaction_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('type_id')->nullable()->constrained('transaction_types');
            $table->foreignId('parent_id')->nullable()->constrained('transaction_categories');
            $table->string('name', 100);
            $table->string('icon',100);
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('transaction_categories');
    }


    private function addInitialData(): void
    {
        DB::table('transaction_categories')->insert([
            ['name' => 'Ініціація балансу', 'icon' => 'mdi-cash-plus', 'type_id' => null],
            ['name' => 'Корекція балансу', 'icon' => 'mdi-cash-edit', 'type_id' => null],
            ['name' => 'Без категорії', 'icon' => 'mdi-help', 'type_id' => 1],
            ['name' => 'Без категорії', 'icon' => 'mdi-help', 'type_id' => 2],
            ['name' => 'Переказ', 'icon' => 'mdi-swap-horizontal', 'type_id' => 3],
        ]);
    }
};
