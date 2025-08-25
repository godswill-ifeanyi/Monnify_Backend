<?php

namespace Database\Seeders;

use App\Models\MonthlyFee;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MonthlyFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MonthlyFee::create([
            "amount" => 750.00,
        ]);
    }
}
