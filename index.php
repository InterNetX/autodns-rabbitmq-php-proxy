<?php

$config = new Yaf_Config_Ini('config.ini');
$rabbitmq_host =$config->get('rabbitmq_host');
$rabbitmq_port =$config->get('rabbitmq_port');
$rabbitmq_user =$config->get('rabbitmq_user');
$rabbitmq_pass =$config->get('rabbitmq_pass');
$rabbitmq_vhost=$config->get('rabbitmq_vhost');
$rabbitmq_queue=$config->get('rabbitmq_queue');



require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

if ( $_SERVER['REQUEST_METHOD'] !== 'POST' && ! php_sapi_name() == "cli" ) {
	http_response_code(405);
	exit(0);
}

try {
	$connection = new AMQPStreamConnection($rabbitmq_host, $rabbitmq_port, $rabbitmq_user, $rabbitmq_pass, $rabbitmq_vhost);
} catch (Exception $e){
	error_log("Can't connect to rabbitmq:" .$e->getMessage());
	http_response_code(500);
	exit(0);
}

try {
	$channel = $connection->channel();

	$channel->queue_declare($rabbitmq_queue, false, false, false, false);

	$content=stream_get_contents( STDIN );
	$msg = new AMQPMessage( $content );
	$channel->basic_publish($msg, '', $rabbitmq_queue);
} catch (Exception $e){
	error_log("Error while processing msg:" .$e->getMessage());
	http_response_code(500);
	exit(0);
}

error_log("Successfully processed msg from AutoDNS");

