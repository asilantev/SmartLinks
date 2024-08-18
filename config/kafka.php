<?php

return [
    'brokers' => env('KAFKA_BROKERS', 'kafka:9092'),
    'auto_commit' => env('KAFKA_AUTO_COMMIT', true),
    'offset_reset' => env('KAFKA_OFFSET_RESET', 'latest'),
    'consumer_group_id' => env('KAFKA_CONSUMER_GROUP_ID', 'laravel-group'),
];
