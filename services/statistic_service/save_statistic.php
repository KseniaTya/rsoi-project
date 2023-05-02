<?php



$conf = new RdKafka\Conf();
$conf->set('metadata.broker.list', 'kafka:9092');
$conf->set('group.id', 'myConsumerGroup');

$consumer = new RdKafka\KafkaConsumer($conf);
$consumer->subscribe(['logs']);

while (true) {
    // объявление данного объекта в цикле, чтобы в случае, если база данных была удалена - не надо было бы перезапускать контейнер
    $db = new SQLite3('db/statistic.db');
    $db->exec("CREATE TABLE IF NOT EXISTS statistic(id INTEGER PRIMARY KEY, message TEXT, service TEXT, username TEXT, datetime TEXT)");

    $message = $consumer->consume(12 * 1000);
    if ($message->err == RD_KAFKA_RESP_ERR_NO_ERROR) {
        $data = json_decode($message->payload);
        $db->exec("INSERT INTO statistic(message, service, username, datetime) VALUES('$data->message', '$data->service',  '$data->token', '".date("Y-m-d H:i:s")."')");
    }

}