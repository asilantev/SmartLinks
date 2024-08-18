<?php

namespace App\Listeners\ConsumerMessageListeners;

use App\Brokers\TopicNameEnum;
use App\Events\ConsumerMessageEvent;
use App\Models\ConditionType;
use App\Models\RedirectRule;
use App\Models\RuleCondition;
use Illuminate\Contracts\Queue\ShouldQueue;

class RuleConditionMessageListener implements ShouldQueue
{
    public function handler(ConsumerMessageEvent $event): void
    {
        $body = $event->message->getBody();

        if ($body['deleted']) {
            $model = RuleCondition::query()->firstWhere('external_id', '=', $body['id']);
            $model->delete();
        } else {
            $data = $body['data'];

            $ruleId = RedirectRule::query()->firstWhere('external_id', '=', $data['rule_id'])->id;
            $conditionTypeId = ConditionType::query()->firstWhere('external_id', '=', $data['condition_type_id'])->id;

            RuleCondition::query()->updateOrCreate(
                ['external_id' => $body['id']],
                [
                    'rule_id' => $ruleId,
                    'condition_type_id' => $conditionTypeId,
                    'condition_value' => $data['condition_value']
                ]
            );
        }
    }

    public function shouldQueue(ConsumerMessageEvent $event): bool
    {
        return $event->message->getTopicName() === TopicNameEnum::RuleCondition->name;
    }
}
