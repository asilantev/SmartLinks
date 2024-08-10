<?php

namespace Database\Factories;

use App\Models\RedirectLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RedirectLog>
 */
class RedirectLogFactory extends Factory
{
    protected $model = RedirectLog::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_ip' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'referer' => $this->faker->url,
            'redirect_url' => $this->faker->url
        ];
    }
}
