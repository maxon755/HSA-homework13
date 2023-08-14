<?php

require __DIR__ . '/vendor/autoload.php';

use xobotyi\beansclient\Beanstalkd;
use xobotyi\beansclient\Client;
use xobotyi\beansclient\Socket\SocketsSocket;

$sock   = new SocketsSocket(host: 'beanstalkd', port: 11300, connectionTimeout: 2);
$client = new Client(socket: $sock, defaultTube: 'myAwesomeTube');

##            ##
#   PRODUCER   #
##            ##

$job = $client->put("job's payload");

var_dump($job);


$client->watchTube('myAwesomeTube2');

$job = $client->reserve();

if ($job) {
    echo "Hey, i received first {$job['payload']} of job with id {$job['id']}\n";

    $client->delete($job['id']);

    echo "And i've done it!\n";
}
else {
    echo "So sad, i have nothing to do";
}

echo "Am I still connected? \n" . ($client->socket()->isConnected() ? 'Yes' : 'No') . "\n";
