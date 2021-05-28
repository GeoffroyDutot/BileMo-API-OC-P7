# BileMo-API-OC-P7 

## Author
[Geoffroy Dutot](https://geoffroydutot.fr)  - 2021

[contact@geoffroydutot.fr](mailto:contact@geoffroydutot.fr)

## Badge  
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/3377866484f8418498cd96f11276bd58)](https://www.codacy.com/gh/GeoffroyDutot/BileMo-API-OC-P7/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=GeoffroyDutot/BileMo-API-OC-P7&amp;utm_campaign=Badge_Grade)

## Introduction

This project is the 7th project of the [Developer PHP / Symfony](https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony) formation of [Openclassrooms](https://openclassrooms.com/). 

The goal of this project is to create an API for BileMo enterprise which offers a catalog of mobile phones in Business to Business. 

The API would be created with Symfony and have an authentication with JTW Token and present an api documentation.

It must respect the rules of levels 1, 2 and 3 of the Richardson model.

## Build with 

-   Symfony 5.2
-   NelmioBundle API-Doc-Bundle
-   Lexik JWT-Authentication-Bundle

## Requirements 

-   PHP 7.4
-   Composer
-   Web server
-   MYSQL

## Installation

-   Clone / Download the project

-   Configure your web server to point on the project directory
    
    Nb: If you have an apache server, you should add this in your apache config file :
        
        RewriteEngine on
        RewriteCond %{HTTP:Authorization} ^(.*)
        RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]

-   Composer install

- Run following commands :
    
    `mkdir -p config/jwt`

    `openssl genrsa -out config/jwt/private.pem -aes256 4096`
    
    `openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem`

-   Copy the .env.sample file and rename it to .env 

-   Edit the .env file to connect it with your database server and add path file for public and private jwt key and add you passphrase

-   Run the command to create the database :  `php bin/console doctrine:database:create`

## Demo data

You can add demo data in database to test the API.

  `php bin/console doctrine:fixtures:load`
