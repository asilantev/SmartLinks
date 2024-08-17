<?php

namespace App\Brokers\Kafka\Consumer;

use App\Brokers\Kafka\Message\AbstractMessage;
use App\Interfaces\Broker\Consumer\ConsumerMessageInterface;

class ConsumerMessage extends AbstractMessage implements ConsumerMessageInterface
{
    public function __construct(
        protected ?string $topicName,
        protected ?int $partition,
        protected ?array $headers,
        protected mixed $body,
        protected mixed $key,
        protected ?int $offset,
        protected ?int $timestamp,
    ) {
        parent::__construct(
            $this->topicName,
            $this->partition,
            $this->headers,
            $this->body,
            $this->key
        );
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }
}
