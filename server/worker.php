<?php

// 1. Include Composer's autoloader (This was missing!)
require __DIR__ . '/vendor/autoload.php';

use Apps\OrderService;
use Spiral\RoadRunner\GRPC\Server;
use Spiral\RoadRunner\Worker;
use Demo\UserServiceInterface;
use Apps\UserService;
use Demo\OrderServiceInterface;

include __DIR__ . '/Services/UserService.php';
include __DIR__ . '/Services/OrderService.php';

try {
    $worker = Worker::create();

    // 4. Initialize the gRPC Server wrapper
    $server = new Server(
        new \Spiral\RoadRunner\GRPC\Invoker()
    );
    // 5. Register your service implementation
    $server->registerService(UserServiceInterface::class, new UserService());
    $server->registerService(OrderServiceInterface::class, new OrderService());

    // 6. Start the blocking event loop to handle incoming HTTP/2 gRPC requests
    $server->serve($worker);
} catch (\Throwable $th) {
    echo "Error: " . $th->getMessage() . PHP_EOL;
    echo "Please run 'composer install' to install dependencies." . PHP_EOL;
    exit(1);
}
