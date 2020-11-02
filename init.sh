#!/bin/bash
var_path=$PWD
echo 'git : subModule update'
git submodule init
git submodule update
echo 'dockerCompose : Initialisation'
ENVFILE=.dockerCompose/.env
ENVFILEEXAMPLE=.dockerCompose/.env.exemple
if test -f "$ENVFILE"; then
    echo "dockerCompose : $ENVFILE exists."
else
    echo "dockerCompose : generate default env file"
    cp  $ENVFILEEXAMPLE $ENVFILE
fi

read -p "project Name (default : lra)? " projectName
projectName=${projectName:-lra}

up_app(){
    docker-compose -f .dockerCompose/docker-compose.yml --env-file .dockerCompose/.env -p $projectName up -d
}

read -p "dockerCompose : Do you wish to up the app [y/n]?" yn
yn=${yn:-n}
if [ "$yn" = "y" ]; then
up_app;
fi


nbContainer=$(docker ps -a --no-trunc --filter name=${projectName}_ | wc -l)
if [ "$nbContainer" -gt "0" ]; then
   echo "dockerCompose : project container are running"
   echo "composer : install" 
   docker-compose -f .dockerCompose/docker-compose.yml --env-file .dockerCompose/.env -p $projectName exec --user=laradock workspace composer install -d /var/www/
   #docker-compose exec --user=laradock workspace php project1/artisan preset none

   read -p "laravel : Do you wish to publish [y / n] ?" yn
   yn=${yn:-n}
   if [ "$yn" = "y" ]; then
   docker-compose -f .dockerCompose/docker-compose.yml --env-file .dockerCompose/.env -p $projectName exec --user=laradock workspace php /var/www/artisan vendor:publish
   fi
   read -p "laravel : Do you wish to migrate [y / n] ?" yn
   yn=${yn:-n}
   if [ "$yn" = "y" ]; then
   docker-compose -f .dockerCompose/docker-compose.yml --env-file .dockerCompose/.env -p $projectName exec --user=laradock workspace php /var/www/artisan migrate
   fi

   read -p "laravel : Do you wish to seed [y / n] ?" yn
   yn=${yn:-n}
   if [ "$yn" = "y" ]; then
   docker-compose -f .dockerCompose/docker-compose.yml --env-file .dockerCompose/.env -p $projectName exec --user=laradock workspace php /var/www/artisan db:seed
   fi  

   read -p "laravel : Do you wish to install default User [y / n] ?" yn
   yn=${yn:-n}
   if [ "$yn" = "y" ]; then
   docker-compose -f .dockerCompose/docker-compose.yml --env-file .dockerCompose/.env -p $projectName exec --user=laradock workspace php /var/www/artisan user:initUserRole
   fi               

    echo "***********************************************************"
    echo "dockerCompose : project built ( localhost )"
    echo "dockerCompose : ssh Access ( in dockerCompose folder : docker-compose -p ${projectName} exec workspace bash  )"
    echo "dockerCompose : Stop Container ( docker-compose stop )"
    echo "***********************************************************"

else
   echo "dockerCompose : project container are not running" 
fi




