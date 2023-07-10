<?php

declare(strict_types=1);

namespace App\CreateUser;

use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('consume:users:create', 'Consumes messages after user creation')]
final class CreateUserConsoleCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Consuming messages');

        $conf = new Conf();
        // Set a rebalance callback to log partition assignments (optional)
        $conf->setRebalanceCb(function (KafkaConsumer $kafka, $err, array $partitions = null) {
            switch ($err) {
                case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
                    echo "Assign: ";
                    var_dump($partitions);
                    $kafka->assign($partitions);
                    break;

                case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
                    echo "Revoke: ";
                    var_dump($partitions);
                    $kafka->assign(NULL);
                    break;

                default:
                    throw new \Exception($err);
            }
        });
        $conf->set('metadata.broker.list', 'purple-clouds_kafka_1:9092');
        $conf->set('group.id', 'userCreateConsumerGroup');
        $conf->set('auto.offset.reset', 'earliest');
        $conf->set('enable.partition.eof', 'true');
        $consumer = new KafkaConsumer($conf);
        $consumer->subscribe(['USERS']);

        echo "Waiting for partition assignment... (make take some time when\n";
        echo "quickly re-joining the group after leaving it.)\n";

        while (true) {
            $message = $consumer->consume(120 * 1000);

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    var_dump($message);
                    break;
                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    echo "No more messages; will wait for more\n";
                    break;
                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    echo "Timed out\n";

                    return Command::FAILURE;
                default:
                    throw new \Exception($message->errstr(), $message->err, Command::FAILURE);
            }
        }
    }
}