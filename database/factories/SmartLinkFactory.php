<?php

namespace Database\Factories;

use App\Models\SmartLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SmartLink>
 */
class SmartLinkFactory extends Factory
{
    protected $model = SmartLink::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug,
            'default_url' => $this->faker->url,
            'expires_at' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
