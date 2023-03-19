<?php

$ampq_connection = new AMQPConnection([
	'host' => 'rabbit',
	'port' => '5672',
	'login' => 'guest',
	'password' => 'guest',
]);
try {
$ampq_connection->connect();
} catch (\Exception $e) {
	echo $e->getMessage() , PHP_EOL;
}

$ampq_channel = new AMQPChannel($ampq_connection);
try {
	$exchange_name = 'exchange';
	$exchange = new AMQPExchange($ampq_channel);
	$exchange->setType(AMQP_EX_TYPE_FANOUT);
	$exchange->setName($exchange_name);
	var_dump($exchange->declareExchange());

	//Do not declasre the queue name by setting AMQPQueue::setName()
	$queue = new AMQPQueue($ampq_channel);
//	$queue->setFlags(AMQP_EXCLUSIVE);
	$queue->declareQueue();
	$queue->bind($exchange_name,$queue->getName());
	echo sprintf("Queue Name: %s", $queue->getName()), PHP_EOL;
	
} catch (Exception $e) {
	var_dump($e->getMessage());
}

//Read from stdin
$message = implode(' ',array_slice($argv,1));
if(empty($message))
	$message = "info: Hello World!";

$exchange->publish($message, '');

echo " [X] Sent {$message}", PHP_EOL;

try {
	$queue->consume(function(AMQPEnvelope $envelope, AMQPQueue $q) {
		var_dump($envelope->getBody());
		$q->ack($envelope->getDeliveryTag());
		return true;
	});
} catch (AMQPException $e) {
	echo $e->getMessage();
}

$ampq_connection->disconnect();
