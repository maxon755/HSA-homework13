<?php

use parallel\Runtime;
use parallel\Future;

$scenario = $argv[1];

$time = 5;
$concurrency = 3;

require_once __DIR__ . '/' . $scenario;
/** @var Scenario $scenarioClass */
$scenarioClass = getScenarioClassName();
echo $scenario . PHP_EOL;
echo $scenarioClass::description() . PHP_EOL;
echo "Concurrency: $concurrency" . PHP_EOL;
echo "Execution time: $time seconds" . PHP_EOL . PHP_EOL;

// Preparation
$preparationTask = function (string $scenario, string $scenarioClass) {
    require_once __DIR__ . '/' . $scenario;
    $scenario = new $scenarioClass();

    $scenario->prepare();
};
$runtimes = array_map(fn() => new Runtime(), range(1, $concurrency));
foreach ($runtimes as $i => $runtime) {
    $futures[] = $runtime->run($preparationTask, [$scenario, getScenarioClassName(), $time]);
}
waitForFuturesDone($futures);
echo PHP_EOL;


// benchmarking
$benchmarkTask = function (string $scenario, string $scenarioClass, int $time) {
    require_once __DIR__ . '/' . $scenario;
    $scenario = new $scenarioClass();

    echo "EXECUTION". PHP_EOL;

    $executionCount = 0;
    $startTime = microtime(true);

    while ($scenario->execute()) {
        $executionCount++;

        $executionTime = microtime(true) - $startTime;

        if ($executionTime >= $time) {
            break;
        }
    }
    $scenario->cleanup();
    return $executionCount;
};

$runtimes = array_map(fn() => new Runtime(), range(1, $concurrency));

$startTime = microtime(true);
$futures = [];
foreach ($runtimes as $i => $runtime) {
    $futures[] = $runtime->run($benchmarkTask, [$scenario, getScenarioClassName(), $time]);
}
waitForFuturesDone($futures);
$totalExecutions = array_reduce($futures, fn($sum, Future $future) => $sum + $future->value());
$totalExecutionTime =  microtime(true) - $startTime;
$totalExecutionTime = round($totalExecutionTime, 3);

$executionRate =  (int) ($totalExecutions / $time);

echo <<<OUTPUT
Real execution time: $totalExecutionTime seconds
Number of executions: $totalExecutions
Executions per second: $executionRate

OUTPUT;

function getScenarioClassName(): string
{
    $declaredClasses = get_declared_classes();

    return array_pop($declaredClasses);
}

/**
 * @param Future[] $futures
 *
 * @return void
 */
function waitForFuturesDone(array $futures): void
{
    do {
        usleep(1);
        $allDone = array_reduce(
            $futures,
            function (bool $c, Future $future): bool {
                return $c && $future->done();
            },
            true
        );
    } while (false === $allDone);
}
