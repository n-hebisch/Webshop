<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 01.10.18
 * Time: 09:38
 */

namespace AppBundle\Controller;


use AppBundle\AppBundle;
use AppBundle\Entity\Address;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Coupon;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Wishlist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Tests\Fixtures\ToString;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login", methods="GET")
     */
    public function getloginAction()
    {
        //if user is already logged in show products
        if ($this->get('session')->has('id')) {
            return $this->redirectToRoute('product_index');
        } //else show login form
        else {
            return $this->render('login/login.html.twig');
        }
    }

    /**
     * @Route("/login", methods="POST")
     */
    public function postloginAction()
    {
        if (!empty($_POST)) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            //check email verification
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();

            $sql = '
            SELECT email_verify FROM user u
            WHERE email = :email
            ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $response = $stmt->fetchAll();

            if ($response[0]['email_verify'] === 'true') {

                if (isset($email) && isset($password)) {
                    $conn = $this->getDoctrine()->getEntityManager()->getConnection();

                    $sql = '
            SELECT id,email,password FROM user u
            WHERE email = :email
            ';
                    $stmt = $conn->prepare($sql);
                    $stmt->execute(['email' => $email]);
                    $response = $stmt->fetchAll();


                    if (empty($response)) {
                        $this->addFlash('error', 'wrong e-mail');
                        return $this->render('login/login.html.twig', ['email' => $email]);
                    } else {

                        if (!password_verify($password, $response[0]['password'])) {
                            $this->addFlash('error', 'wrong password');
                            return $this->render('login/login.html.twig', ['email' => $email]);
                        } else {
                            $this->addFlash('success', 'login successful!');

                            $this->get('session')->set('id', $response[0]['id']);
                            return $this->redirectToRoute('product_index');
                        }
                    }

                }
                //if email is not verified
            } else if ($response[0]['email_verify'] !== 'true') {
                $this->addFlash('error', 'we sent an e-mail to your given address, please verify first :)');
                return $this->render('login/login.html.twig', ['email' => $email]);
            }


        }
    }

    /**
     * @Route("/logout", methods="GET")
     */
    public function logoutAction()
    {
        $this->get('session')->invalidate();
        $this->addFlash('success', 'your logged out');
        return $this->redirectToRoute('product_index');
    }
}