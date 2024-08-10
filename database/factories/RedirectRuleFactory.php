<?php

namespace Database\Factories;

use App\Models\RedirectRule;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RedirectRule>
 */
class RedirectRuleFactory extends Factory
{
    protected $model = RedirectRule::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'target_url' => $this->faker->url,
            'priority' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean,
        ];
    }
}
