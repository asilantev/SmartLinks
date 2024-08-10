<?php

namespace Database\Seeders;

use App\Models\ConditionType;
use Illuminate\Database\Seeder;

class ConditionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConditionType::create([
            'code' => 'time_interval',
            'name' => 'Интервал'
        ]);
        ConditionType::create([
            'code' => 'platform',
            'name' => 'Платформа'
        ]);
    }
}
