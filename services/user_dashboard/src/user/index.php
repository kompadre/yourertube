<?php
session_start();

var_dump($_FILES['video']);

echo "Former task id: " , ($_SESSION['former_task_id'] ?? '[none yet]'), PHP_EOL;  



if (isset($_FILES['video'])) {
	$uploaded_file = '/files/uploaded/' . $_FILES['video']['name'];
	move_uploaded_file($_FILES['video']['tmp_name'], $uploaded_file);
	var_dump($uploaded_file);
}

$ampq_connection = new AMQPConnection([
	'host' => getenv('AMQP_HOST'),
	'port' => getenv('AMQP_PORT'),
	'login' => getenv('AMQP_USER'),
	'password' => getenv('AMQP_PASS'),
]);

try {
	$ampq_connection->connect();
} catch (\Exception $e) {
	error_log($e->getMessage(), E_ERROR);
}

$ampq_channel = new AMQPChannel($ampq_connection);
try {
	$exchange_name = 'exchange';
	$exchange = new AMQPExchange($ampq_channel);
	$exchange->setType(AMQP_EX_TYPE_FANOUT);
	$exchange->setName($exchange_name);
} catch (Exception $e) {
	var_dump($e->getMessage());
}

$exchange->publish($message, json_encode(['' ]));
$ampq_channel->close();
$ampq_connection->disconnect();

