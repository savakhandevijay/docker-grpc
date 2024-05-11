# How to setup
- copy or git clone your php repository to `src` directory
- .env file has GRPC_PORT, currently its 9001, which host machine can connect to grpc on 9001 port
- add correct php directory path under `unified` service `volume` section
- Added static ip address to docker images, you can change as per your requirement
- when docker image build successfully, Roadrunner serve with `environments/dev/config/cex/grpc/.rr-uk.yaml` config file, which point to UK territory
- `/var/log` server folder is mapped with local `logs` folder, which help to trace and map all the server logs to this directory eg, wss, roadrunners, php all logs written here

# How to run GRPC
- copy grpc folder from repo, place it outside repo eg. desktop or download
- follow the instruction to setup GRPC request in Postman [here](https://learning.postman.com/docs/sending-requests/grpc/grpc-request-interface/)
- once GRPC request created, select the `Service Defination` tab of that request, click on Import as API button underneath, select the below folder `grpc/proto/v3/member/memberservice.proto`
- GRPC client required this *.proto files to get Services defination, parameters and return type, which will provided by this `*.proto` files
- Add the `grpc://127.0.0.1:9001` path as GRPC server path in request section whereas left side section will have dropdown of serivces listed by `*.proto` files
- click on `Message` tab, select the sample message from `use example message` bottom button, to get dummy service data to pass.
