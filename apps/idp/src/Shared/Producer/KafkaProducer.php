<?php

declare(strict_types=1);

namespace App\Shared\Producer;

use App\Shared\Topic;
use RdKafka\Conf;
use RdKafka\Producer;

final readonly class KafkaProducer
{
    private Producer $producer;

    public function __construct()
    {
        $conf = new Conf();
        $conf->set('log_level', (string)LOG_DEBUG);
        $conf->set('debug', 'all');
        $conf->set('metadata.broker.list', 'purple-clouds_kafka_1:9092');
        //If you need to produce exactly once and want to keep the original produce order, uncomment the line below
        $conf->set('enable.idempotence', 'true');

        $this->producer = new Producer($conf);
    }

    public function produce(string $message, Topic $topic): void
    {
        $topic = $this->producer->newTopic($topic->name);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $this->producer->flush(3600);
    }
}