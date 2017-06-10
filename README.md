# API BASE SYMFONY

```sh 
  composer create-project api-code/base-symfony YOUR_FOLDER -s dev
``` 

This is a fork of [https://api-platform.com/](https://api-platform.com/ "API platform"). Is for personal use, and including
- Configuration basic of api platform
- Integrate a JWT 
- Doctrine migrations
- JMose scheduler for cron jobs
- Email with spool command!

Remember, is for PHP >= 7.0

## BEGIN
```sh 

bin/console doctrine:database:create
bin/console doctrine:schema:create
 # or the best way for databse, use migrations!!
bin/console doctrine:migrations
                  :diff     Generate a migration by comparing your current database to your mapping information.
                  :execute  Execute a single migration version up or down manually.
                  :generate Generate a blank migration class.
                  :migrate  Execute a migration to a specified version or the latest available version.
                  :status   View the status of a set of migrations.
                  :version  Manually add and delete migration versions from the version table.

bin/console assets:install
bin/console fos:user:create  # Important for a Token!
bin/console server:start

# For Jmose scheduler
bin/console scheduler:execute

# Email with spool, program a scheduler with this strategy
bin/console swiftmailer:spool:clear-failures
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
In each request add this header
Authorization: Bearer tokenJWT 
