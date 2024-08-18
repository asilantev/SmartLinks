<?php

namespace Database\Seeders;

use App\Models\RedirectRule;
use App\Models\RuleCondition;
use App\Models\SmartLink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmartLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        SmartLink::factory()
//            ->count(10)
//            ->create()
//            ->each(function ($smartLink) {
//                $rules = RedirectRule::factory()
//                    ->count(rand(1, 5))
//                    ->make();
//
//                $smartLink->redirectRules()->saveMany($rules);
//
//                $rules->each(function ($rule) {
//                    $conditions = RuleCondition::factory()
//                        ->count(rand(1, 3))
//                        ->make();
//
//                    $rule->conditions()->saveMany($conditions);
//                });
//            });

        $smartLink = SmartLink::create([
            'slug' => 'specific-link',
            'default_url' => 'https://example.com',
            'expires_at' => now()->addMonths(6),
        ]);
        $redirectRules = $smartLink->redirectRules();
        $conditions = $redirectRules->create([
            'target_url' => 'https://target.com',
            'priority' => 1,
            'is_active' => true,
        ])->conditions();
        $conditions->create([
            'condition_type_id' => 2,
            'condition_value' => json_encode(['value' => 'Linux']),
        ]);
        $conditions->create([
            'condition_type_id' => 1,
            'condition_value' => json_encode(['start' => new \DateTime(), 'end' => (new \DateTime())->add(new \DateInterval('PT2M'))]),
        ]);
    }
}
