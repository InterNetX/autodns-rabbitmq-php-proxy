# autodns-rabbitmq-php-proxy

Sample implementation for retrieving AutoDNS PUSH notifications and send them to a RabbitMQ Queue.

You need to copy config.ini.example to config.ini and edit it.

## config.ini

 [rabbitmq]
 host=localhost
 port=5672
 user=user
 pass=password
 vhost=/
 queue=autodns_response
