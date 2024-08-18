<?php

namespace App\Interfaces\Broker;

interface MessageInterface
{
    public function getKey(): mixed;

    public function getTopicName(): ?string;

    public function getPartition(): ?int;

    public function getBody();
    public function getHeaders(): ?array;
}
