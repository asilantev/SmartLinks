<?php

namespace App\Interfaces\Broker\Consumer;

use App\Interfaces\Broker\MessageInterface;

interface ConsumerMessageInterface extends MessageInterface
{
    public function getOffset(): ?int;
    public function getTimestamp(): ?int;
}
