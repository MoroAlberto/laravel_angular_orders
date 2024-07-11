# Dockerized stack for Laravel 11 + Angular 17 + MySQL

Based on: https://github.com/ucrem/docker-laravel-angular public template

A just installed **backend** container with:
* Laravel 11 
* PHP 8.3
* Angular 17 + Clarity
* Supervisor with php command artisan queue:work
* OPTIONAL: Zscaler certificate

A **frontend** container with the official dockerhub node 20 image and angular CLI 18

A **mysql** container with official mysql: latest dockerhub image

A **phpmyadmin** container with the official dockerhub image phpmyadmin / phpmyadmin, linked to the mysql container in order to access the DB

A **nginx** webserver container with the official image of the dockerhub nginx: alpine

## Init Docker

The /docker/backend/supervisor/supervisord.conf file is linked in the backend container. Editing that file is instantly replicated to the container

### First Installation

To begin the installation, follow these steps:

Copy the `.env.example` file to create your own environment configuration file. This file will be used to set up the project and the database user for MySQL:

   ```sh
   cp .env.example .env
   ```

#### Zscaler:

If you are using Zscaler, you need to install the Zscaler root certificate in WSL (Windows Subsystem for Linux). Zscaler intercepts SSL connections, terminating them at its end and creating a new SSL connection to the destination using its own certificate. To enable WSL to connect with this relayed connection, you must install a copy of the Zscaler root certificate.

1. Export a copy of the certificate from the Windows certificate store:
2. Open `certmgr.msc`.
3. In the left-hand panel, click on "Trusted Root Certification Authorities".
4. In the right-hand panel, scroll down to the entry "Zscaler Root CA".
5. Right-click on it and select "All Tasks -> Export...".
6. Choose "Base-64 encoded X.509 (.cer)".
7. Rename the exported file from `.cer` to `.crt`.
8. Place the `cacert.crt` file into the `/docker/backend` folder.
9. Uncomment the relevant lines in `/docker/backend/php.ini`.
10. Uncomment the relevant lines in `docker-compose.yml`.

Next, build the Docker containers:

```sh
docker-compose build
```

Wait for the configuration process to complete. Then start the containers:

```sh
docker-compose up -d
```

Once all the containers are initialized, connect to the backend container:

```sh
docker exec -it PROJECT_NAME_backend /bin/bash
```

If you did not change the project name, use:

```sh
docker exec -it local_backend /bin/bash
```

You will be in the `/var/www/backend` directory.

Proceed by copying the `.env.example` file again and updating it with the same MySQL database name, user, and password as in the Docker instance:

```sh
cp .env.example .env
```

Then, install all dependencies:

```sh
composer install
```

Generate the application key and run the database migrations with seed:

```sh
php artisan key:generate
php artisan migrate --seed
```

For the backend it is not necessary to execute the command php artisan because the nginx container is linked on the public laraver folder

As for the frontend (Angular) I made sure to expose the 4200, in this way you edit the file locally but links it on the docker instantly and angular cli does the rest on the docker.

If Angular doesn't start, try to:
```sh
docker exec -it local_frontend /bin/bash
```
then
```sh
npm start
```

So here are the links currently configured:

backend: `localhost:8000`

frontend: `localhost:4200`

phpmyadmin: `localhost:7000`


## Steps to Delete a Docker Build

### 1. List Docker Images

First, list all Docker images to identify the build you want to delete. Use the following command:

```sh
docker images
```

This command will display a list of all images with their corresponding image IDs, repository names, tags, and sizes.

### 2. Identify the Docker Image

From the list of images, identify the image you want to delete. Note down the `IMAGE ID` or `REPOSITORY:TAG` of the target image.

### 3. Delete the Docker Image

To delete a specific Docker image, use the following command:

```sh
docker rmi <IMAGE ID or REPOSITORY:TAG>
```
or

```sh
docker rmi myapp:latest
```

### 4. Force Delete (if necessary)

If the image is being used by a stopped container, you may encounter an error. To force delete the image, use the `-f` flag:

```sh
docker rmi -f <IMAGE ID or REPOSITORY:TAG>
```

### 5. List Docker Containers

Sometimes, you may also want to remove containers associated with a build. First, list all containers:

```sh
docker ps -a
```

This command will display a list of all containers, including stopped ones, with their corresponding container IDs and names.

### 6. Delete Docker Containers

To delete a specific Docker container, use the following command:

```sh
docker rm <CONTAINER ID or NAME>
```

Replace `<CONTAINER ID or NAME>` with the appropriate value. For example:

```sh
docker rm 1a2b3c4d5e6f
```

or

```sh
docker rm mycontainer
```

### 7. Clean Up Unused Resources

To clean up all unused images, containers, volumes, and networks, you can use the `docker system prune` command. This is useful to free up disk space:

```sh
docker system prune -a
```

You will be prompted to confirm the action. Type `y` and press Enter.

### Additional Tips

- **Check Disk Usage:** To see how much disk space Docker is using, you can use the following command:

  ```sh
  docker system df
  ```

- **Remove Dangling Images:** Dangling images are layers that are not tagged and are not referenced by any container. To remove them, use:

  ```sh
  docker image prune
  ```

