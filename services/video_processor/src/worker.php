<?php
require_once __DIR__ . '/common/vendor/autoload.php';

$q = new \Queue\Queue();
$q->consumeTasks(function(AMQPEnvelope $message, AMQPQueue $q) {
	echo "Message received: " , $message->getBody() , PHP_EOL;
	$data = json_decode($message->getBody(), true);
	if (file_exists($data['filename'])) {
		list('basename' => $basename, 'dirname' => $dirname) = pathinfo($data['filename']);
		$thumbFile = $dirname . '/' . $basename . '.thumb.png';
		exec("ffmpeg -i ". escapeshellarg($data['filename']) ." -vframes 1 " . escapeshellarg($thumbFile));
	}
	$q->ack($message->getDeliveryTag());
});