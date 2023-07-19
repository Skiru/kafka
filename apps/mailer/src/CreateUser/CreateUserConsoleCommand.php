<?php

declare(strict_types=1);

namespace App\CreateUser;

use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('consume:users:create:mailer', 'Consumes messages after user creation and sends an email')]
final class CreateUserConsoleCommand extends Command
{
    private const USERS_TOPIC_NAME = 'USERS';
    private const GROUP_ID = 'mailerUserCreateConsumerGroup';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(
            sprintf(
                'Consuming messages from topic: %s',
                self::USERS_TOPIC_NAME,
            ),
        );

        $conf = new Conf();
        $conf->set('metadata.broker.list', 'purple-clouds_kafka_1:9092');
        $conf->set('group.id', self::GROUP_ID);
        $conf->set('auto.offset.reset', 'earliest');
        $conf->set('enable.partition.eof', 'true');
        $consumer = new KafkaConsumer($conf);

        //Subscribe to topic
        $consumer->subscribe([self::USERS_TOPIC_NAME]);

        while (true) {
            $message = $consumer->consume(10000);

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $output->writeln(
                        sprintf('Consumed: %s', $message->payload)
                    );
                    var_dump($message);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    $output->writeln('No more messages; will wait for more');
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    $output->writeln('Timed out');

                    return Command::FAILURE;
                default:
                    throw new \LogicException($message->errstr(), $message->err);
            }
        }
    }
}