<?php

namespace Apps;

use Demo\OrderdetailsResponse;
use Demo\Orders;
use Demo\OrderServiceInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Demo\WebordersRequest;
use Google\Protobuf\Timestamp;

class OrderService implements OrderServiceInterface
{
    public function GetOrders(ContextInterface $ctx, WebordersRequest $in): OrderdetailsResponse
    {
        // In a real application, you would query your database here using $in->getWeborders()
        $authHeaders = $ctx->getValue('authorization');

        // 2. Validate the token
        $isValid = $authHeaders && isset($authHeaders[0]) && $authHeaders[0] === 'Bearer - eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEsImlhdCI6MTY3Mjc2NjAyOCwiZXhwIjoxNjc0NDk0MDI4fQ.kCak9sLJr74frSRVQp0_27BY4iBCgQSmoT3vQVWKzJg';

        if (!$isValid) {
            // 3. Throw a proper gRPC error if unauthorized
            // 16 is the official gRPC Status Code for UNAUTHENTICATED
            throw new \Spiral\RoadRunner\GRPC\Exception\GRPCException('Invalid or missing authorization token', 16);
        }
        $response = new OrderdetailsResponse();
        $ordersList = [];    
        $orders = [
            '123'=> [
                'webordernumber' => 123,
                'originalwebordernumber' => 123,
                'isactive' => true,
                'orderdatetime' => new \DateTime()->setDate(2024, 5, 20)->setTime(10, 15),
                'paymentname' => 'Paypal',
            ],
            '456'=> [
                'webordernumber' => 456,
                'originalwebordernumber' => 456,
                'isactive' => true,
                'orderdatetime' => new \DateTime()->setDate(2024, 6, 1)->setTime(14, 30),
                'paymentname' => 'Card',
            ]
        ];
        foreach ($in->getWeborders() as $orderNumber) {
            if (isset($orders[$orderNumber])) {
                $orderData = $orders[$orderNumber];
                $order = new Orders();
                $order->setWebordernumber($orderData['webordernumber']);
                $order->setOriginalwebordernumber($orderData['originalwebordernumber']);
                $order->setIsactive($orderData['isactive']);
                // Set the orderdatetime to the current time
                $timestamp = new Timestamp();
                $timestamp->fromDateTime($orderData['orderdatetime']);
                $order->setOrderdatetime($timestamp);
                $order->setPaymentname($orderData['paymentname']);

                $ordersList[] = $order;
            }
        }
        
        $response->setOrderList($ordersList);

        // You can set additional fields in the response as needed
        return $response;
    }
}