<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 30.08.18
 * Time: 10:28
 */

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Niklas;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Faker;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Tests\Fixtures\ToString;


class NiklasController extends Controller
{
    /**
     * @Route("/genus/new")
     */
    public function doctrineAction()
    {
        $em = $this->getDoctrine()->getManager();
        $generator = \Faker\Factory::create();
        $populator = new Faker\ORM\Doctrine\Populator($generator, $em);

        $populator->addEntity(Niklas::class, 1, array(
            'name' => function () use ($generator) {
                return $generator->firstName();
            },
            'value' => function () use ($generator) {
                return $generator->ipv6();
            },
            'updated' => function () use ($generator) {
                return $generator->dateTime();
            },
            'created' => function () use ($generator) {
                return $generator->dateTime();
            }
        ));
        $genus = $populator->execute();

//        $faker = Faker\Factory::create();
////        $now = new \DateTime('now');
//        $genus = new Niklas();
//        $genus->setName($faker->firstName);
//        $genus->setValue($faker->ipv6);
//        $genus->setUpdated($faker->dateTime);
//        $genus->setCreated($faker->dateTime);


//        $em->persist($genus);
//        $em->flush();

        return new Response('<html><body>Genus created!</body></html>');

    }

    /**
     * @Route("/genus/show")
     */
    public function showAction()
    {
//        $totals = $this->getDoctrine()->getRepository(Niklas::class)->findAll();

        return $this->render('niklas/base.html.twig', array(
//            'totals' => $totals
        ));
    }
}