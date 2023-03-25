<?php

namespace Queue;

use AMQPConnection;
use AMQPChannel;
use AMQPQueue;


class Queue
{
	private ?AMQPConnection $connection = null;
	private ?AMQPChannel $channel = null;
	private ?AMQPQueue $queue = null;
	private ?\AMQPExchange $exchange = null;
	private bool $connected = false;

	const ROUTING_KEY = 'urrtb.tasks';

	function __construct($connectionConfig = null)
	{
		if ($connectionConfig == null) {
			$connectionConfig = [
				'host' =>     getenv('AMQP_HOST'),
				'port' =>     getenv('AMQP_PORT'),
				'auth' =>     getenv('AMQP_USER'),
				'password' => getenv('AMQP_PASS'),
			];
		}
		$this->connection = new AMQPConnection($connectionConfig);
		try {
			$this->connection->connect();
			//Create and declare channel
			$this->channel = new AMQPChannel($this->connection);
			$this->channel->setPrefetchCount(1);
			$this->queue = new AMQPQueue($this->channel);
			$this->queue->setName(self::ROUTING_KEY);
			$this->queue->setFlags(AMQP_DURABLE);
			$this->queue->declareQueue();
		} catch (\Exception $e) {
			error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString(), E_NOTICE);
		}
		$this->connected = true;
	}


	function __destruct()
	{
		if ($this->connected) $this->connection->disconnect();
	}

	public function publishTask(array|string|\Serializable $taskData, $attributes=[]): bool
	{
		if ($this->exchange == null) {
			$this->exchange = new \AMQPExchange($this->channel);
		}

		return $this->exchange->publish(
			json_encode($taskData),
			self::ROUTING_KEY,
			AMQP_NOPARAM,
			$attributes
		);
	}

	public function consumeTasks($callback)
	{
		try {
			echo ' [*] Waiting for logs. To exit press CTRL+C', PHP_EOL;
			$this->queue->consume($callback);
		} catch (\Exception $e) {
			error_log($e->getMessage() . PHP_EOL . $e->getTraceAsString(), E_NOTICE);
			exit();
		}
	}

}