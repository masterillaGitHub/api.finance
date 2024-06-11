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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('symbol', 10);
            $table->string('alphabetic_code', 3);
            $table->string('numeric_code', 3);
            $table->string('name', 50);
            $table->string('country_name', 50);
            $table->string('country_code', 2);
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
        Schema::dropIfExists('currencies');
    }

    private function addInitialData(): void
    {
        $data = collect([
            ['UA', 'Україна', 'Гривня', '₴', 'UAH', 980],
            ['US', 'США', 'Долар США', '$', 'USD', 840],
            ['EU', 'Євросоюз', 'Євро', '€', 'EUR', 978],
        ])
            ->map(fn(array $currency) => $this->currencyRow($currency))
            ->toArray();

        DB::table('currencies')->insert($data);
    }

    private function currencyRow(array $row): array
    {
        [$countryCode, $countryName, $name, $symbol, $alphabeticCode, $numericCode] = $row;

        return [
            'country_code' => $countryCode,
            'country_name' => $countryName,
            'name' => $name,
            'symbol' => $symbol,
            'alphabetic_code' => $alphabeticCode,
            'numeric_code' => $numericCode
        ];
    }
};
