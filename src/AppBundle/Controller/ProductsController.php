<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProductsController extends Controller
{
    /**
     * Example normal route
     * @Route("/")
     */
    public function ListAction(Request $resquest)
    {
        return new Response("Bienvenidos");
    }

    /**
     * @Route("/public/products")
     */
    public function productsAction(Request $resquest)
    {
        $encoders = array(new XmlEncoder(), new JsonEncode());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();
        $jsonContent = $serializer->serialize($products, 'json');
        return new Response($jsonContent);
    }

    /**
     * Example with route yml
     * @return array
     */
    public function specialAction()
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository(Product::class)->findAll();
        return $products;
    }

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
}
