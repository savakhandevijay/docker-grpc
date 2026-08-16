
docker run --rm -v $(pwd):/app -w /app alpine:latest \
    sh -c "apk add --no-cache protobuf grpc-plugins && \
           protoc --php_out=./shared --grpc_out=generate_server:./shared \
           --plugin=protoc-gen-grpc=/usr/bin/grpc_php_plugin ./proto/user.proto"