<?php
declare(strict_types = 1);

use Depotwarehouse\Jeopardy\Board\BoardFactory;
use Depotwarehouse\Jeopardy\Server;
use React\EventLoop\Factory;

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$question_filename = $argv[1] ?? getenv('CURRENT_GAME');
echo 'Starting game "' . $question_filename . '"' . PHP_EOL;

$server = new Server(Factory::create());

try {
    $boardFactory = new BoardFactory($question_filename);
    $server->run($boardFactory);
} catch (\React\Socket\ConnectionException $exception) {
    echo 'Error occurred: ' . get_class($exception) . "\n";
    echo $exception->getMessage();
    die();
}

