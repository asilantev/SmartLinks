<?php

namespace App\Listeners\ConsumerMessageListeners;

use App\Brokers\TopicNameEnum;
use App\Events\ConsumerMessageEvent;
use App\Models\ConditionType;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConditionTypeMessageListener implements ShouldQueue
{
    public function handler(ConsumerMessageEvent $event): void
    {
        $body = $event->message->getBody();

        if ($body['deleted']) {
            $model = ConditionType::query()->firstWhere('external_id', '=', $body['id']);
            $model->delete();
        } else {
            $data = $body['data'];
            ConditionType::query()->updateOrCreate(
                ['external_id' => $body['id']],
                [
                    'code' => $data['code'],
                    'name' => $data['name'],
                ]
            );
        }
    }

    public function shouldQueue(ConsumerMessageEvent $event): bool
    {
        return $event->message->getTopicName() === TopicNameEnum::ConditionType->name;
    }
}
