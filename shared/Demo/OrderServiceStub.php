<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Demo;

/**
 * The service definition
 */
class OrderServiceStub {

    /**
     * @param \Demo\WebordersRequest $request client request
     * @param \Grpc\ServerContext $context server request context
     * @return \Demo\OrderdetailsResponse for response data, null if if error occurred
     *     initial metadata (if any) and status (if not ok) should be set to $context
     */
    public function GetOrders(
        \Demo\WebordersRequest $request,
        \Grpc\ServerContext $context
    ): ?\Demo\OrderdetailsResponse {
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
            '/demo.OrderService/GetOrders' => new \Grpc\MethodDescriptor(
                $this,
                'GetOrders',
                '\Demo\WebordersRequest',
                \Grpc\MethodDescriptor::UNARY_CALL
            ),
        ];
    }

}
