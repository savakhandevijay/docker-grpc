<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Demo;

/**
 * The service definition
 */
class UserServiceStub {

    /**
     * @param \Demo\UserRequest $request client request
     * @param \Grpc\ServerContext $context server request context
     * @return \Demo\UserResponse for response data, null if if error occurred
     *     initial metadata (if any) and status (if not ok) should be set to $context
     */
    public function GetUser(
        \Demo\UserRequest $request,
        \Grpc\ServerContext $context
    ): ?\Demo\UserResponse {
        $context->setStatus(\Grpc\Status::unimplemented());
        return null;
    }

    /**
     * Get the method descriptors of the service for server registration
     *
     * @return array of \Grpc\MethodDescriptor for the service methods
     */
    public final function getMethodDescriptors(): array
    {
        return [
            '/demo.UserService/GetUser' => new \Grpc\MethodDescriptor(
                $this,
                'GetUser',
                '\Demo\UserRequest',
                \Grpc\MethodDescriptor::UNARY_CALL
            ),
        ];
    }

}
