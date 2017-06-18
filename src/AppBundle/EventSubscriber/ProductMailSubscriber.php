<?php

namespace AppBundle\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use AppBundle\Entity\Product;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class ProductMailSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $em;
    protected $authorizationChecker;
    protected $token;
    
    public function __construct(\Swift_Mailer $mailer, EntityManager $em, AuthorizationCheckerInterface $authorizationChecker, \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage $token_storage)
    {
        $this->mailer = $mailer;
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
        $this->token = $token_storage;
    }

    public static function getSubscribedEvents()
    {
        return [
            //KernelEvents::VIEW => [['sendMail', EventPriorities::POST_WRITE]],
            KernelEvents::VIEW => [['accionDemo', EventPriorities::POST_WRITE]]
        ];
    }

    public function accionDemo(GetResponseForControllerResultEvent $event)
    {
        $product = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        
        if (!$product instanceof Product || Request::METHOD_POST !== $method) {
            return;
        }
        $this->authorizationChecker->isGranted("ROLE_USER");
        $user = $this->token->getToken()->getUser();
        $product->setDescription($product->getDescription() . ': ' . 'Modifing my entity!! by ' . $user);
        $this->em->flush();

    }

    public function sendMail(GetResponseForControllerResultEvent $event)
    {
        $product = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$product instanceof Product || Request::METHOD_POST !== $method) {
            return;
        }

        $message = \Swift_Message::newInstance()
            ->setSubject('A new book has been added')
            ->setFrom('system@example.com')
            ->setTo('contact@les-tilleuls.coop')
            ->setBody(sprintf('The book #%d has been added.', $product->getId()));

        $this->mailer->send($message);
    }
}