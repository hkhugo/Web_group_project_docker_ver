# Web_group_project_docker version 
Web_group_project with Apache, MySql 8.0, PhpMyAdmin and Php

## Docker Desktop  
---------------------------------------------------------------
Download and install Docker Desktop

[Docker Desktop](https://www.docker.com/products/docker-desktop/) 

 ##Guideline
---------------------------------------------------------------

First, create the docker-compose file (e.g. similar to the one in /ref folder).

To run these containers:

```
docker-compose up -d
```
## Check database
-----------------------------------------------------
Open phpmyadmin at [http://localhost:8000](http://localhost:8000).

## Check website
---------------------------------------------------------------
Open website(index.php) [http://localhost:8001](http://localhost:8001)

## Run mysql client:
---------------------------------------------------------------
```
docker-compose exec db mysql -u root -p
```
