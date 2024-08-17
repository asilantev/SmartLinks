<?php

namespace App\Events;

use App\Interfaces\Broker\Consumer\ConsumerMessageInterface;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConsumerMessageEvent
{
    use SerializesModels, Dispatchable;

    public function __construct(public ConsumerMessageInterface $message)
    {
    }
}
