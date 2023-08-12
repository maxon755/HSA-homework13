<?php

declare(strict_types=1);

require_once __DIR__ . '/../Scenario.php';

class RedisQueueWrite implements \Scenario
{
    private Redis $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->connect('redis');

    }

    public function execute() : bool
    {
        $this->redis->lPush('test_queue', random_int(0, 999));

        return true;
    }

    public function prepare() : void
    {
        echo "Preparing for something great" . PHP_EOL;
    }

    public function cleanup() : void
    {
        $this->redis->del('test_queue');
        $this->redis->close();
    }

    public static function description() : string
    {
        return 'Writing to redis queue (list)';
    }
}
