<?php
namespace App\Brokers\Kafka\Message;

use App\Interfaces\Broker\MessageInterface;

abstract class AbstractMessage implements MessageInterface
{
    public function __construct(
        protected ?string  $topicName = null,
        protected ?int    $partition = RD_KAFKA_PARTITION_UA,
        protected ?array  $headers = [],
        protected mixed   $body = [],
        protected mixed   $key = null,
    ) {
    }

    public function getTopicName(): ?string
    {
        return $this->topicName;
    }

    public function getPartition(): ?int
    {
        return $this->partition;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function getKey(): mixed
    {
        return $this->key;
    }
}
