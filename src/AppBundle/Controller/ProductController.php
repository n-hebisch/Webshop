<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Address;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Coupon;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Wishlist;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Faker;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Tests\Fixtures\ToString;

class ProductController extends Controller
{
    /**
     * check login status
     * @return bool
     */
    private function checkLogin()
    {
        if ($this->get('session')->get('id')) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * @Route("/product/index/{offset}/{limit}", name="product_index")
     */
    public function indexAction($offset = null, $limit = null)
    {
        $loggedIn=$this->checkLogin();
        $repository = $this->getDoctrine()->getRepository(Product::class);

        if ($offset !== null && $limit !== null) {
            $query = $repository->createQueryBuilder('u')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->getQuery();

            $products = $query->getResult();
        } else {
            $products = $repository->findAll();
        }
        return $this->render('product/index.html.twig', array(
            'products' => $products, 'loggedIn'=> $loggedIn
        ));
    }

//    /**
//     * @Route("/product/create", name="create")
//     */
//    public function createAction()
//    {
//        return $this->render('user/create.html.twig');
//    }
//
//    /**
//     * @Route("/product/store", name="store", methods={"POST"})
//     */
//    public function storeAction()
//    {
//        $username = $_POST["username"];
//        $email = $_POST["email"];
//        $password = $_POST["password"];
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $user = new User();
//        $user->setUsername($username);
//        $user->setEmail($email);
//        $user->setPassword($password);
//
//        $entityManager->persist($user);
//        $entityManager->flush();
//
//
//        return $this->redirectToRoute('index');
//    }
//
//    /**
//     * @Route("/product/edit/{id}", name="edit")
//     */
//    public function editAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository(Product::class)->find($id);
//        $addresses = $em->getRepository(Address::class)->findBy(['user' => $id]);
//
//        return $this->render('user/edit.html.twig', ['user' => $user, 'addresses' => $addresses]);
//    }
//
//    /**
//     * @Route("/product/update/{id}", name="update", methods={"PUT"})
//     */
//    public function updateAction($id, Request $request)
//    {
//        $params = array();
//        $content = $request->getContent();
//        if (!empty($content)) {
//            $params = json_decode($content, true);
//        }
//
//        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository(Product::class)->find($id);
//
//        $user->setUsername($params["username"]);
//        $user->setEmail($params["email"]);
//        $user->setPassword($params["password"]);
//
//        $em->flush();
//
//        return $this->redirectToRoute('index');
//    }
//
//    /**
//     * @Route("/product/destroy/{id}", name="destroy", methods={"DELETE"})
//     */
//    public function destroyAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $post = $em->getRepository(Product::class)->find($id);
//
//        if (!$post) {
//            return $this->redirectToRoute('index');
//        }
//
//        $em->remove($post);
//        $em->flush();
//
//        return $this->redirectToRoute('index');
//    }
}