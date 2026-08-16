# PHP gRPC Microservice Demo (RoadRunner & PHP-FPM)

This project demonstrates a production-grade gRPC microservice architecture using PHP. 

Traditional PHP-FPM cannot act as a gRPC server because it relies on a short-lived request/response cycle, whereas gRPC requires long-lived HTTP/2 multiplexed connections. To solve this, we use **RoadRunner** (a Go-based application server) to handle the network layer and pass requests to long-running PHP workers.

## Architecture
* **Client Frontend:** Nginx + PHP-FPM (Exposed on `localhost:8080`)
* **Load Balancer:** HAProxy (Routes gRPC traffic on port `50051`)
* **Backend Server:** RoadRunner (Listens on `9001`) + PHP CLI Workers

---

## 🚀 Quick Start Setup

### 1. Directory Structure
Create the following structure:
```
grpc-demo/
├── client/
│   ├── Dockerfile
│   ├── composer.json
│   └── index.php
├── proto/
│   └── user.proto
├── server/
│   ├── .rr.yaml
│   ├── Dockerfile
│   ├── composer.json
│   └── worker.php
├── shared/            # Auto-generated Protobuf classes go here
├── docker-compose.yml
├── haproxy.cfg
└── nginx.conf
```
**Note:** Here in the docker-compose.yml we have `webserver:latest` image name. That you can fetch and tagged as webserver:latest from https://github.com/savakhandevijay/docker-images/ nginx_php84 dockerfile.

### 2. Generate Protobuf Classes (Cross-Platform)
To avoid local compiler installation issues (especially on ARM64/Apple Silicon), use a temporary Docker container to compile your .proto file:
```
docker run --rm -v $(pwd):/app -w /app alpine:latest \
    sh -c "apk add --no-cache protobuf grpc-plugins && \
           protoc --php_out=./shared --grpc_out=./shared \
           --plugin=protoc-gen-grpc=/usr/bin/grpc_php_plugin \$(find ./proto -name '*.proto')""
```

### 3. Manually Create the RoadRunner Interface
The standard PHP protoc plugin only generates client stubs. For RoadRunner, manually create the interface file at `shared/Demo/UserServiceInterface.php` added in the repo

### 4. Install Dependencies & Boot Up
```
# Install dependencies for both client and server
docker-compose run --rm client-fpm composer install
docker-compose run --rm server-rr composer install

# Start the stack
docker-compose up -d
```
Visit http://localhost:8080/?id=99 in your browser. You should see a JSON response fetched via gRPC!

---

## 🐛 Troubleshooting & Known Issues Log

During the development of this stack, we encountered and solved several common Docker/gRPC issues. If you hit an error, check this log:

### 1. `protoc`: executable file not found (ARM64 / Apple Silicon)
- **Symptom:** Generating the protobuf files fails with architecture mismatch errors.
- **Cause:** Older `grpc-php` Docker images are built strictly for AMD64 architecture. 
- **Fix:** We bypassed pre-built images and used `alpine:latest` to install `protobuf` and `grpc-plugins` directly via `apk` on the fly.

### 2. HAProxy Error: `could not resolve address 'roadrunner'`
- **Symptom:** HAProxy container crashes immediately on startup.
- **Cause:** `haproxy.cfg` was targeting the hostname `roadrunner`, but the Docker Compose service was actually named `server-rr`.
- **Fix:** Updated the backend block in `haproxy.cfg` to use the correct Docker Compose service name:
  ```haproxy
  server rr1 server-rr:9001 check proto h2
  ```

### 3. Class GPBMetadata\Proto\User not found
- **Symptom:** 500 error from the FPM client, or the RoadRunner worker crashing in the background.
- **Cause:** The protoc compiler generates a hidden GPBMetadata folder containing binary schemas. Composer was not configured to autoload it.
- **Fix:** Added "GPBMetadata\\": "../shared/GPBMetadata/" to the psr-4 block in both composer.json files and ran composer dump-autoload.

### 4. Interface Demo\UserServiceInterface not found
- **Symptom:** RoadRunner worker throws a fatal error and crashes on boot.
- **Cause:** The official gRPC PHP plugin only generates Client stubs, not Server interfaces. RoadRunner requires a specific interface extending ServiceInterface.
- **Fix:** Manually created the interface file at shared/Demo/UserServiceInterface.php.

### 5. 502 Bad Gateway (Connection Refused to Client FPM)
- **Symptom:** Nginx cannot reach PHP-FPM on port 9000.
- **Cause:** Nginx heavily caches internal IP addresses. It cached an old IP address for the FPM container after a container restart.
- **Fix:** Restarted Nginx (docker-compose restart client-web) to force it to flush the DNS cache. (Note: We also ensured zlib1g-dev was installed in the FPM Dockerfile to prevent the grpc C-extension from causing a segmentation fault).

### 6. Spiral\RoadRunner\GRPC\Server::__construct() null given
- **Symptom:** Worker script throws a fatal type error on line 32.
- **Cause:** RoadRunner v3.x removed support for passing null as the first argument to the Server constructor.
- **Fix:** Instantiated the server by passing the Invoker directly as the first argument:
    ```
    $server = new Server(
        new \Spiral\RoadRunner\GRPC\Invoker()
    );
    ```
### 7. RoadRunner Error: validation: unable to run http service
- **Symptom:** RoadRunner container crashes when enabling debug: true.
- **Cause:** The pool configuration was accidentally placed under an http block in .rr.yaml, waking up the standard web plugin without an assigned port.
- **Fix:** Moved the pool: debug: true configuration strictly under the grpc: block. This enables hot-reloading, forcing RoadRunner to recreate the PHP worker on every request so you don't have to restart the container when modifying PHP code.

### 8. Name does not resolve syscall: getaddrinfo (haproxy:50051)
- **Symptom:** The FPM client throws this DNS resolution error when requesting data.
- **Cause:** The HAProxy container shut down (likely because server-rr crashed during troubleshooting in a previous step), removing haproxy from Docker's internal DNS registry.
- **Fix:** Ran docker-compose up -d to tell Docker to bring all missing and stopped containers back online.