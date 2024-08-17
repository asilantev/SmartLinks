<?php

namespace App\Providers;

use App\Events\ConsumerMessageEvent;
use App\Listeners\ConsumerMessageListeners;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        ConsumerMessageEvent::class => [
            ConsumerMessageListeners\ConditionTypeMessageListener::class,
            ConsumerMessageListeners\SmartLinkMessageListener::class,
            ConsumerMessageListeners\RedirectRuleMessageListener::class,
            ConsumerMessageListeners\RuleConditionMessageListener::class
        ]
    ];
}
