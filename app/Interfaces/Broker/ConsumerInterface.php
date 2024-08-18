<?php

namespace App\Interfaces\Broker;

interface ConsumerInterface
{
    public function consume(): void;
}
