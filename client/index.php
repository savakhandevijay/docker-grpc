<?php

// 1. Include Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

use Demo\UserRequest;
use Demo\UserServiceClient;
use Grpc\ChannelCredentials;
try {
    $client = new UserServiceClient('haproxy:50051', [
        'credentials' => ChannelCredentials::createInsecure(),
    ]);

    // // 3. Prepare the Protobuf message
    $request = new UserRequest();
    // Grab the ID from the URL if provided (default to 42)
    // Test this by visiting: http://localhost:8080/?id=99
    $userId = isset($_GET['id']) ? (int)$_GET['id'] : 42;
    $request->setUserId($userId);
    // 1. Define your metadata (keys must be lowercase!)
    $metadata = [
        'authorization' => ['Bearer - eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEsImlhdCI6MTY3Mjc2NjAyOCwiZXhwIjoxNjc0NDk0MDI4fQ.kCak9sLJr74frSRVQp0_27BY4iBCgQSmoT3vQVWKzJg'],
        'x-custom-client-id' => ['fpm-frontend-v1']
    ];

    // 2. Pass the metadata as the second argument
    //  Make the Unary gRPC call
    /** @var \Demo\UserResponse $response */
    /** @var \stdClass $status */
    list($response, $status) = $client->GetUser($request, $metadata)->wait();

    // 5. Handle canonical status codes strictly
    if ($status->code !== Grpc\STATUS_OK) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'grpc_status_code' => $status->code,
            'message' => $status->details
        ]);
        exit;
    }

    // 6. Output the successful gRPC response to the browser as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'id' => $response->getId(),
        'name' => $response->getName(),
        'email' => $response->getEmail(),
        'message' => 'Successfully fetched via gRPC!'
    ]);

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "Please run 'composer install' to install dependencies." . PHP_EOL;
    exit(1);
}



