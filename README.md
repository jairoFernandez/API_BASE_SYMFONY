# API BASE SYMFONY

```sh 
  composer create-project api-code/base-symfony YOUR_FOLDER -s dev
``` 

This is a fork of [https://api-platform.com/](https://api-platform.com/ "API platform"). Is for personal use, and including
- Configuration basic of api platform
- Integrate a JWT 
- Doctrine migrations, fixtures and extensions.
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

## Add more controllers
##### First option
Routes with yml in app/config/routing.yml
```sh
# app/config/routing.yml
book_special:
    path: '/productsjairo/{id}/special'
    methods:  ['GET']
    defaults:
        _controller: 'AppBundle:Products:special'
        _api_resource_class: 'AppBundle\Entity\Product'
        _api_item_operation_name: 'special'
```

##### Second option
Router with annotations in each action of a controller

```sh
# In a controller
 /**
     * Example with annotations
     * @Route(
     *     name="demo_special",
     *     path="/demo/{id}/special",
     *     defaults={"_api_resource_class"=Product::class, "_api_item_operation_name"="specialdemo"}
     * )
     * @Method("GET")
     */
    public function demoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();
        return $products;
    }

```

## Subscribe events for modify request

In src/AppBundle/EventSubscriber add a file name ProductMailSubscriber.php
```sh

<?php

// other uses
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class ProductMailSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $em;
    protected $authorizationChecker;
    protected $token;

    public function __construct(\Swift_Mailer $mailer, EntityManager $em, AuthorizationCheckerInterface $authorizationChecker, \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $token_storage)
    {
        // Initilize vars!!!
    }

     public static function getSubscribedEvents()
    {
        return [
            //KernelEvents::VIEW => [['sendMail', EventPriorities::POST_WRITE]],
            KernelEvents::VIEW => [['accionDemo', EventPriorities::POST_WRITE]]
        ];
    }

```

For more events [https://api-platform.com/docs/core/events](https://api-platform.com/docs/core/events)

## Add custom filter
```sh
<?php

... other use

/**
 * Product
 * @ApiResource(attributes={"filters"={"regexp"}})  /--->>>Add filter regexp
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
{
```

Now in the url you can:
```sh

../api/products?id=[1,2]&number=20&description=otr

```
