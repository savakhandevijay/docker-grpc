<?php

namespace Demo;

use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\ServiceInterface;

interface OrderServiceInterface extends ServiceInterface
{
    public const NAME = "demo.OrderService";
    // The RPC method signature
    public function GetOrders(ContextInterface $ctx, WebordersRequest $in): OrderdetailsResponse;
}