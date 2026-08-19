<?php

namespace Apps;

use Demo\UserServiceInterface;
use Spiral\RoadRunner\GRPC\ContextInterface;
use Demo\UserRequest;
use Demo\UserResponse;
use Spiral\RoadRunner\GRPC\Exception\GRPCException;

class UserService implements UserServiceInterface
{
    public function GetUser(ContextInterface $ctx, UserRequest $in): UserResponse
    {
        // In a real application, you would query your database here using $in->getUserId()
        $authHeaders = $ctx->getValue('authorization');

        // 2. Validate the token
        $isValid = $authHeaders && isset($authHeaders[0]) && $authHeaders[0] === 'Bearer - eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEsImlhdCI6MTY3Mjc2NjAyOCwiZXhwIjoxNjc0NDk0MDI4fQ.kCak9sLJr74frSRVQp0_27BY4iBCgQSmoT3vQVWKzJg';

        if (!$isValid) {
            // 3. Throw a proper gRPC error if unauthorized
            // 16 is the official gRPC Status Code for UNAUTHENTICATED
            throw new GRPCException('Invalid or missing authorization token', 16);
        }
        $response = new UserResponse();
        $response->setId($in->getUserId());
        $response->setName("Alex The Beginner");
        $response->setEmail("alex@example.com");

        return $response;
    }
}
