<?php

declare(strict_types=1);

namespace App\Shared\Producer;

use App\Shared\Topic;
use RdKafka\Conf;
use RdKafka\Producer;
use RdKafka\TopicConf;

final readonly class KafkaProducer
{
    private Producer $producer;

    public function __construct()
    {
        $conf = new Conf();
        $conf->set('log_level', (string)LOG_DEBUG);
        $conf->set('debug', 'all');
        $conf->set('metadata.broker.list', 'purple-clouds_kafka_1:9092');

        $conf->set('enable.idempotence', 'true');

        $this->producer = new Producer($conf);
    }

    public function produce(string $message, Topic $topic): void
    {
        $topicConf = new TopicConf();
//        $topicConf->set('num.partitions', '2');
        $topic = $this->producer->newTopic($topic->name, $topicConf);

        //Produce randomly
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);

        $this->producer->poll(0);

        $this->producer->flush(10000);
    }
}