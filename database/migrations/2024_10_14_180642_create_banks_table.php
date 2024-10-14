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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('image_url',100)->nullable();
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
        Schema::dropIfExists('banks');
    }

    private function addInitialData(): void
    {
        $data = collect([
            'Monobank',
        ])
            ->map(fn(string $name) => ['name' => $name])
            ->toArray();

        DB::table('banks')->insert($data);
    }
};
