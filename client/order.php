<?php

// 1. Include Composer's autoloader
require __DIR__ . '/vendor/autoload.php';

use Demo\WebordersRequest;
use Demo\OrderServiceClient;
use Grpc\ChannelCredentials;

try {
    $client = new OrderServiceClient('haproxy:50051', [
        'credentials' => ChannelCredentials::createInsecure(),
    ]);

    $request = new WebordersRequest();
    $weborders = isset($_GET['weborders']) ? json_decode($_GET['weborders'], true) : ["123", "456"];
    $request->setWeborders($weborders);
    $metadata = [
        'authorization' => ['Bearer - eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEsImlhdCI6MTY3Mjc2NjAyOCwiZXhwIjoxNjc0NDk0MDI4fQ.kCak9sLJr74frSRVQp0_27BY4iBCgQSmoT3vQVWKzJg'],
        'x-custom-client-id' => ['fpm-frontend-v1']
    ];

    list($response, $status) = $client->GetOrders($request, $metadata)->wait();

    // 5. Handle canonical status codes strictly
    if ($status->code !== Grpc\STATUS_OK) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => true,
            'grpc_status_code' => $status->code,
            'message' => $status->details,
        ]);
        exit;
    }
    $orders = [];
    foreach ($response->getOrderList() as $order) {
        $orders[] = [
            'webordernumber' => $order->getWebordernumber(),
            'originalwebordernumber' => $order->getOriginalwebordernumber(),
            'isactive' => $order->getIsactive(),
            'orderdatetime' => $order->getOrderdatetime()->toDateTime()->format('Y-m-d H:i:s'),
            'paymentname' => $order->getPaymentname(),
        ];
    }
    // 6. Output the successful gRPC response to the browser as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'orders' => $orders,
        'message' => 'Successfully fetched via gRPC!'
    ]);

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "Please run 'composer install' to install dependencies." . PHP_EOL;
    exit(1);
}
