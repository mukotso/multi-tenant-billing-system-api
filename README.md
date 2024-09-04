<h1 align="center">MULTI-TENANT UTILITY BILLING SYSTEM API </h1>

## Getting Started
Clone this project to your local machine and open it in your favourite IDE
```bash
git clone https://github.com/mukotso/multi-tenant-billing-system-api.git
```
## Running locally
Copy .env.example to .env. by running the following command in your terminal

```bash
cp .env.example .env 
```

## Installation With Docker
I have included docker-compose.yml and Dockerfile together with scripts (install.sh) to handle the installation of the required services and dependencies  and (uninstall.sh) - destroy created docker containers.

Give execution permission to this scripts by running
```bash
chmod +x install.sh 
chmod +x uninstall.sh 
```
Now run the install script to create  containers, Ngnix configuration,MySQL database container , it will compose this containers and start them in detached mode

```bash
 ./install.sh 
```


This script does the following:

- Builds a docker image from the Dockerfile
- Installs composer packages
- Generates application keys
- Runs database migrations
- Seeds data to the database
- Creates jwt secrets
- Obtains necessary permissions for the sessions and the local storage

After running the installation script, you should be able to access the application via http://localhost:8001
