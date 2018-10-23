<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 07.09.18
 * Time: 14:30
 */

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Address;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Coupon;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Wishlist;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Faker;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Tests\Fixtures\ToString;

class AmazonController extends Controller
{
    /**
     * @Route("/generate/data")
     */
    public function doctrineRandomDataAction()
    {
        $em = $this->getDoctrine()->getManager();
        $generator = Faker\Factory::create();
        $populator = new Faker\ORM\Doctrine\Populator($generator, $em);

        $populator->addEntity(Address::class, 1, array(
            'city' => function () use ($generator) {
                return $generator->city();
            }, 'country' => function () use ($generator) {
                return $generator->country();
            }, 'street' => function () use ($generator) {
                return $generator->streetName();
            }, 'number' => function () use ($generator) {
                return $generator->buildingNumber();
            },
        ));
        $populator->addEntity(Cart::class, 1, array(
            'amount' => function () use ($generator) {
                return $generator->randomFloat(3, 0, 1000);
            }
        , 'numberOfArticles' => function () use ($generator) {
                return $generator->numberBetween(0, 100);
            }
        ));
        $populator->addEntity(\AppBundle\Entity\Category::class, 1, array(
            'category_name' => function () use ($generator) {
                return $generator->company;
            }
        ));
        $populator->addEntity(Coupon::class, 1, array(
            'coupon_code' => function () use ($generator) {
                return $generator->hexColor;
            }, 'discount' => function () use ($generator) {
                return $generator->numberBetween(1, 50);
            }
        ));
        $populator->addEntity(Product::class, 1, array(
            'name' => function () use ($generator) {
                return $generator->lastName;
            }, 'price' => function () use ($generator) {
                return $generator->randomFloat(3, 0, 1000);
            }
        ));
        $populator->addEntity(User::class, 1, array(
            'username' => function () use ($generator) {
                return $generator->firstName();
            }, 'password' => function () use ($generator) {
                return $generator->password();
            }, 'email' => function () use ($generator) {
                return $generator->email();
            }
        ));
        $populator->addEntity(Wishlist::class, 1, array(
            'listname' => function () use ($generator) {
                return $generator->firstName();
            }
        ));


        $genus = $populator->execute();

        return new Response('<html><body>Created!</body></html>');

    }
}