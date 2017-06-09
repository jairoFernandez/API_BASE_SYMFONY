# API BASE SYMFONY

```sh 
  composer create-project api-code/base-symfony YOUR_FOLDER -s dev
``` 

This is a fork of [https://api-platform.com/](https://api-platform.com/ "API platform"). Is for personal use, and including
- Configuration basic of api platform
- Integrate a JWT 

Remember, is for PHP >= 7.0

## BEGIN
```sh 
bin/console doctrine:database:create
bin/console doctrine:schema:create
bin/console fos:user:create  # Important for a Token!
bin/console server:start
``` 
http://localhost:8000/docs

## Cambiar llaves de JWT
```sh 
$ mkdir -p var/jwt # For Symfony3+, no need of the -p option
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
``` 
## Obtain a token
We use [https://github.com/lexik/LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle "JWT!")
```sh
curl -X POST http://localhost:8000/api/login_check -d _username=johndoe -d _password=test
```

## Header
Authorization: Bearer tokenJWT 
