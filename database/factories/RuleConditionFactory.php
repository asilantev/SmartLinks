<?php

namespace Database\Factories;

use App\Models\ConditionType;
use App\Models\RuleCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RuleCondition>
 */
class RuleConditionFactory extends Factory
{
    protected $model = RuleCondition::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = ConditionType::all()->random();

        return [
            'condition_type_id' => $type->id,
            'condition_value' => $this->getConditionValue($type->code),
        ];
    }

    private function getConditionValue($type)
    {
        switch ($type) {
            case 'time_interval':
                return json_encode([
                    'start' => $this->faker->time(),
                    'end' => $this->faker->time(),
                ]);
            case 'platform':
                return json_encode([
                    'value' => $this->faker->randomElement(['Linux', 'Windows', 'MacOS']),
                ]);
            case 'browser':
                return json_encode([
                    'name' => $this->faker->randomElement(['Chrome', 'Firefox', 'Safari']),
                ]);
            default:
                return json_encode([
                    'custom_field' => $this->faker->word,
                ]);
        }
    }
}
