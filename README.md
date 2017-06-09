# API BASE SYMFONY

This is a fork of [https://api-platform.com/](https://api-platform.com/ "API platform"). Is for personal use, and including
- Configuration basic of api platform
- Integrate a JWT 

Remember, is for PHP >= 7.0

## BEGIN
```sh 
bin/console server:start
``` 
http://localhost:8000/docs

## Cambiar llaves de JWT
```sh 
$ mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
``` 

## Header
Authorization: Bearer tokenJWT 
