<?php

namespace Demo;

use Spiral\RoadRunner\GRPC\ContextInterface;
use Spiral\RoadRunner\GRPC\ServiceInterface;

interface UserServiceInterface extends ServiceInterface {
    
    // This MUST match the "package.Service" defined in your proto file
    public const NAME = "demo.UserService";

    // The RPC method signature
    public function GetUser(ContextInterface $ctx, UserRequest $in): UserResponse;
}