<?php

namespace App\Brokers\Kafka\Facades;

use App\Events\ConsumerMessageEvent;
use App\Interfaces\Broker\Consumer\ConsumerMessageInterface;
use App\Interfaces\Broker\ConsumerInterface;
use Junges\Kafka\Contracts\ConsumerMessage;
use Junges\Kafka\Facades\Kafka;

class Consumer implements ConsumerInterface
{
    public function consume(): void
    {
        $consumer = Kafka::consumer()
            ->subscribe(app('Broker.TopicNames'))
            ->withConsumerGroupId(config('kafka.consumer_group_id'))
            ->withHandler(function (ConsumerMessage $message) {
                $messageDto = app(ConsumerMessageInterface::class, [
                    'topicName' => $message->getTopicName(),
                    'partition' => $message->getPartition(),
                    'headers' => $message->getHeaders() ?? [],
                    'body' => $message->getBody(),
                    'key' => $message->getKey(),
                    'offset' => $message->getOffset(),
                    'timestamp' => $message->getTimestamp(),
                ]);

                ConsumerMessageEvent::dispatch($messageDto);
            })
            ->build();

        $consumer->consume();
    }
}
