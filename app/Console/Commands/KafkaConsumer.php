<?php

namespace App\Console\Commands;

use App\Interfaces\Broker\ConsumerInterface;
use Illuminate\Console\Command;

class KafkaConsumer extends Command
{
    protected $signature = 'broker:consume';
    protected $description = 'Consume messages from broker';

    public function handle(ConsumerInterface $consumer)
    {
        $consumer->consume();
    }
}
