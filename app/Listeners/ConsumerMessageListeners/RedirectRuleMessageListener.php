<?php

namespace App\Listeners\ConsumerMessageListeners;

use App\Brokers\TopicNameEnum;
use App\Events\ConsumerMessageEvent;
use App\Models\RedirectRule;
use App\Models\SmartLink;
use Illuminate\Contracts\Queue\ShouldQueue;

class RedirectRuleMessageListener implements ShouldQueue
{
    public function handler(ConsumerMessageEvent $event): void
    {
        $body = $event->message->getBody();

        if ($body['deleted']) {
            $model = RedirectRule::query()->firstWhere('external_id', '=', $body['id']);
            $model->delete();
        } else {
            $data = $body['data'];

            $smartLinkId = SmartLink::query()->firstWhere('external_id', '=', $data['smart_link_id'])->id;

            RedirectRule::query()->updateOrCreate(
                ['external_id' => $body['id']],
                [
                    'smart_link_id' => $smartLinkId,
                    'target_url' => $data['target_url'],
                    'is_active' => $data['is_active'],
                    'priority' => $data['priority'],
                ]
            );
        }
    }

    public function shouldQueue(ConsumerMessageEvent $event): bool
    {
        return $event->message->getTopicName() === TopicNameEnum::RedirectRule->name;
    }
}
