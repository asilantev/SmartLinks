<?php

namespace Tests\Unit\Kafka;

use App\Brokers\Kafka\Consumer\ConsumerMessage;
use Tests\TestCase;

class ConsumerMessageTest extends TestCase
{
    public function testConstructorAndGetters()
    {
        $topicName = 'test-topic';
        $partition = 1;
        $headers = ['header1' => 'value1', 'header2' => 'value2'];
        $body = ['key' => 'value'];
        $key = 'test-key';
        $offset = 1;
        $timestamp = time();

        $message = new ConsumerMessage($topicName, $partition, $headers, $body, $key, $offset, $timestamp);

        $this->assertEquals($topicName, $message->getTopicName());
        $this->assertEquals($partition, $message->getPartition());
        $this->assertEquals($headers, $message->getHeaders());
        $this->assertEquals($body, $message->getBody());
        $this->assertEquals($key, $message->getKey());
        $this->assertEquals($offset, $message->getOffset());
        $this->assertEquals($timestamp, $message->getTimestamp());
    }
}
