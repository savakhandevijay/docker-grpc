# How to setup
- copy or git clone your php repository to `src` directory
- .env file has GRPC_PORT, currently its 9001, which host machine can connect to grpc on 9001 port
- add correct php directory path under `unified` service `volume` section
- Added static ip address to docker images, you can change as per your requirement
- when docker image build successfully, Roadrunner serve with `environments/dev/config/cex/grpc/.rr-uk.yaml` config file, which point to UK territory
- `/var/log` server folder is mapped with local `logs` folder, which help to trace and map all the server logs to this directory eg, wss, roadrunners, php all logs written here
