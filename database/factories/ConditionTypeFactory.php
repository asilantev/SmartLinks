<?php

namespace Database\Factories;

use App\Models\ConditionType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConditionType>
 */
class ConditionTypeFactory extends Factory
{
    protected $model = ConditionType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [
            [
                'code' => 'time_interval',
                'name' => 'Интервал времени',
            ],
            [
                'code' => 'platform',
                'name' => 'Платформа'
            ]
        ];

        return $this->faker->randomElement($types);
    }
}
