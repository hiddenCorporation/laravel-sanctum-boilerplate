# laravel-rest-api

> A Boilerplate to start an api using laravel8, passport, cors package and spatie/permission

## what it is

a boilerplate to create api using laravel 8 and sanctum with a submodule laradock to create quickly a dev environment.

## Installation

### Using GitHub

git clone `https://github.com/hiddenCorporation/laravel-sanctum-boilerplate`

## Configuration

After having install the package, you need to :

**initiate the boilerplate**

A commande has been made to easily set the project run this command in the root folder :

```bash
$ sh init.sh
```

It will retrieve git subModule, install, seed, and migrate your fresh install of laravel (For a default fresh install you want to migrate and set defaut user)


the boilerplate is set like this

- /.laradock

laradock is integrated in this repository as subModule. If you need to  use it to create a dev environment you can 

```bash
git submodule init
git submodule update
```

- /.dockerCompose
Here customisation of the local environment using docker files and structure bring by laradock. 
There is a data folder used for database.
*PgAdmin has been set to help architecture your db, available by default at localhost:5050*
*if you're having problem calling pgAdmin or having trouble with log check permission folder for  .dockerCompose/data, api/storage/logs*

- /api

The laravel8 install

Our laravel install use laravel sanctum : `https://laravel.com/docs/8.x/sanctum`

To call our api you need to create csrf token using the route : localhost/sanctum/csrf-cookie

We've installed by default `https://github.com/spatie/laravel-permission` to help defining permission and role for specifical user

To test your users on route you can use integrated middleware from laravel-permission
`https://spatie.be/docs/laravel-permission/v3/basic-usage/middleware`

