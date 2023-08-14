<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use xobotyi\beansclient\Client;
use xobotyi\beansclient\Socket\SocketsSocket;

require_once __DIR__ . '/../Scenario.php';


class BeanstalkdWrite implements Scenario
{

    private Client $beanstalkdClient;

    public function __construct()
    {
        $socket   = new SocketsSocket(host: 'beanstalkd', port: 11300, connectionTimeout: 2);
        $this->beanstalkdClient = new Client(socket: $socket, defaultTube: 'test_tube');
    }

    public static function description(): string
    {
        return 'test';
    }

    public function prepare(): void
    {
        return;
    }

    public function execute(): bool
    {
        $this->beanstalkdClient->put("test_payload");

        return true;
    }

    public function cleanup(): void
    {
        // TODO: Implement cleanup() method.
    }
}