<?php

namespace App\Listeners\ConsumerMessageListeners;

use App\Brokers\TopicNameEnum;
use App\Events\ConsumerMessageEvent;
use App\Models\SmartLink;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;

class SmartLinkMessageListener implements ShouldQueue
{
    public function handler(ConsumerMessageEvent $event): void
    {
        $body = $event->message->getBody();

        if ($body['deleted']) {
            $model = SmartLink::query()->firstWhere('external_id', '=', $body['id']);
            $model->delete();
        } else {
            $data = $body['data'];
            SmartLink::query()->updateOrCreate(
                ['external_id' => $body['id']],
                [
                    'slug' => $data['slug'],
                    'default_url' => $data['default_url'],
                    'expires_at' => $data['expires_at'] ? Carbon::parse($data['expires_at']) : null,
                ]
            );
        }
    }

    public function shouldQueue(ConsumerMessageEvent $event): bool
    {
        return $event->message->getTopicName() === TopicNameEnum::SmartLink->name;
    }
}
