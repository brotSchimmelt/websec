# Docker Environment
---

This is a short summary how to setup the Docker environment for the WebSec hacking platform.

## Install Docker

You will need Docker (>= **19.03.13**) and docker-compose (>= **1.25.5**) in order to run this environment. To check if the tools are already installed, you can either run the ```setup_docker.sh``` script or run the following commands manually:

```shell
$ docker --version
$ docker-compose --version
```

### Install Docker on macOS
Download and install **Docker Desktop** from [Docker Hub](https://hub.docker.com/editions/community/docker-ce-desktop-mac/). Docker-compose is already included.

### Install Docker on Windows 10 (Pro required)
Download and install **Docker Desktop** from [Docker Hub](https://hub.docker.com/editions/community/docker-ce-desktop-windows/). Docker-compose is already included.

*Hyper-V and Windows Container must be enabled.*

### Install Docker on Linux (Ubuntu)
0. Remove older versions of Docker:
```shell
$ sudo apt-get remove docker docker-engine docker.io containerd runc
```

1. Setup the Docker repository:
```shell
$ sudo apt-get update

$ sudo apt-get install apt-transport-https \
    ca-certificates \
    curl \
    gnupg-agent \
    software-properties-common
```

2. Add the Docker GPG key:
```shell
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
```
**Check the fingerprint** [link](https://docs.docker.com/engine/install/ubuntu/)

```shell
$ sudo apt-key fingerprint <last 8 digits of the fingerprint>
```

3. Add the stable repository for the used Ubuntu version:
```shell
$ sudo add-apt-repository \
   "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
   $(lsb_release -cs) \
   stable"
```

4. Install Docker Engine:
```shell
 $ sudo apt-get update
 $ sudo apt-get install docker-ce docker-ce-cli containerd.io
```

5. Verify the Docker installation:
```shell
$ sudo docker run hello-world
```

[Original Source and Trouble shooting](https://docs.docker.com/engine/install/ubuntu/)

### Post Installation (Linux Only)
1. Create a Docker group:
```shell
$ sudo groupadd docker
```

2. Add the current user to the newly created group:
```shell
$ sudo usermod -aG docker $USER
```

3. Apply the group changes (or log out and back in again):
```shell
$ newgrp docker 
```

4. Test the Docker installation without ```sudo```:
```shell
$ docker run hello-world
```

5. Configure Docker to start at boot:
```shell
$ sudo systemctl enable docker
```

6. Install **docker-compose**:
```shell
$ sudo curl -L "https://github.com/docker/compose/releases/download/1.27.4/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
$ sudo chmod +x /usr/local/bin/docker-compose
```

[Original Source and Trouble shooting](https://docs.docker.com/engine/install/linux-postinstall/)


## Setup the WebSec Environment

0. Disable any instance of apache or MySQL on your host system. On Ubuntu systems (>= 16.04 LTS) you can simply run the following commands to stop apache and prevent it from starting after a reboot:

```shell
$ sudo systemctl disable apache2
$ sudo systemctl stop apache2
```

1. Run the ```setup_docker.sh``` script to set all necessary configurations.

```shell
$ ./setup_docker.sh
```

2. Start all Docker containers with:

```shell
$ docker-compose up -d
```
*The script will set up the MySQL and mail account credentials as well as the port and proxy configurations. The installation option for phpMyAdmin can also be chosen during the setup process.*


<br>

**Manual Setup:** *(not recommended)*
- Copy and rename the ```example.env``` to ```.env``` and set all necessary variables
<br>

- Set the MySQL credentials from the ```.env``` file and the host URI in the php configuration files under **www/config/** (config.php, db_login.php, db_shop.php)
<br>

- If the phpMyAdmin installation should be accessible via the **pma/** directory and not an open port, rename ```Dockerfile_pma``` and ```docker-compose_pma.yml``` to ```Dockerfile``` and ```docker-compose.yml```
<br>

- Change the link to the phpMyAdmin installation in **www/src/includes/admin_sidebar.php**
<br>

- Set the proxy configurations for the WWU network by uncommenting the corresponding lines in the ```Dockerfile```
<br>

- To enable the use of proxies for Docker on your host, run the following lines:

```shell
$ mkdir -p /etc/systemd/system/docker.service.d

$ touch /etc/systemd/system/docker.service.d/http-proxy.conf
    
$ echo "[Service]\nEnvironment=first\nEnvironment=second\n" > \
    /etc/systemd/system/docker.service.d/http-proxy.conf

$ sed -i 's!first!"HTTP_PROXY=http://wwwproxy.uni-muenster.de:3128"!g' \ 
    /etc/systemd/system/docker.service.d/http-proxy.conf

$ sed -i 's!second!"HTTPS_PROXY=http://wwwproxy.uni-muenster.de:3128"!g' \
    /etc/systemd/system/docker.service.d/http-proxy.conf

$ systemctl daemon-reload

$ systemctl restart docker
```
- And change the directory ownership of **www/data/** to the apache user:

```shell
$ sudo chown www-data ./apache_php/www/data &> /dev/null
```

## Running Multiple Instances on The Same Host 

To run multiple instances of the hacking platform on the same host simultaneously, you could simply run the docker-compose command from different directories with individual port configurations and unique names. In this case, every instance has access to its own MySQL databases and **data/** directory.

The ports for the Docker containers can be set in the ```.env``` file. The port configuration also changes the container names automatically to avoid name conflicts on the host.

*For example, the first instance could run with the default ports from the ```example.env``` while a second instance uses port 8081 for apache and the ports 3308/3309 for the MySQL container.*

Shortened output from ```$ docker ps```:
```
PORTS                               NAMES
0.0.0.0:8081->80/tcp                php_apache_8081
0.0.0.0:8083->80/tcp                phpmyadmin_8083
33060/tcp, 0.0.0.0:3308->3306/tcp   db_login_3308
33060/tcp, 0.0.0.0:3309->3306/tcp   db_shop_3309
0.0.0.0:80->80/tcp                  php_apache_80
0.0.0.0:8082->80/tcp                phpmyadmin_8082
33060/tcp, 0.0.0.0:3307->3306/tcp   db_login_3307
0.0.0.0:3306->3306/tcp, 33060/tcp   db_shop_3306
```

**Note:** Do not simply copy the directory of a running instance to create a new one. Better, use a copy of the original source.


## Stopping The Hacking Platform

You can always stop all containers from the hacking platform directory with docker-compose:
```shell
$ docker-compose down
```
In this case all user data in the MySQL databases is untouched. If the container are started again, the hacking platform has the exact same state as before.

To delete all persistent Docker volumes for the MySQL databases **permanently**, run the command with the ```-v``` flag. Note, that the files in the **data/** directory are not effected by this command. In order to delete this files as well, you have to remove them manually.

**Note**: After a reboot of the host system, the hacking platform should be stopped and started again to work properly. This has no effect on any user data.

## Docker-Compose Commands

- **Start all containers**
```shell
$ docker-compose up
```

- **Start all containers in the background**
```shell
$ docker-compose up -d
```

- **Force all images to be rebuild**
```shell
$ docker-compose build --no-cache
```

- **Stop all containers gracefully**
```shell
$ docker-compose down
```

- **Stop all containers and delete all persistent volumes** &#9888;&#65039;
```shell
# deletes all user data from the MySQL databases 
$ docker-compose down -v
```
[Full Documentation](https://docs.docker.com/compose/)

## Docker Commands

- **List all active containers**
```shell
$ docker ps
```

- **Run a command in a running container**
```shell
$ docker exec -it <container_name> <command>

# open bash in php_apache container
$ docker exec -it php_apache /bin/bash
```

- **Resources used by docker container**
```shell
$ docker stats --all
```

- **Kill all running containers**
```shell
$ docker kill $(docker ps -q)
```

- **Delete all containers including it volumes**
```shell
$ docker rm -vf $(docker ps -a -q)
```

- **Delete all images**
```shell
$ docker rmi -f $(docker images -a -q)
```

- **Delete all images in powershell**
```shell
$ $images = docker images -a -q
$ foreach ($image in $images) { docker image rm $image -f } 
```

- **Remove all unused containers, volumes, networks and images**
```shell
$ docker system prune -a -volumes
```

[More Commands](https://docs.docker.com/engine/reference/commandline/docker/)