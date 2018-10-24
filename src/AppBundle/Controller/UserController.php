<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 17.09.18
 * Time: 14:12
 */

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Address;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Coupon;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\Wishlist;
use Mailgun\Mailgun;
use Symfony\Bundle\FrameworkBundle\Tests\Fixtures\Validation\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Faker;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Tests\Fixtures\ToString;
use Symfony\Component\Dotenv\Dotenv;


class UserController extends Controller
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
     *renders twig template with offset/limit userdata
     * @param integer $offset
     * @param integer $limit
     *
     * @return Response|\Exception
     */
    /**
     * @Route("/user/index/{offset}/{limit}", name="index")
     */
    public function indexAction($offset = null, $limit = null)
    {
        $loggedIn = $this->checkLogin();
        if ($loggedIn) {
            $users = [];

            if (isset($offset) && isset($limit)) {
                $users = $this->getUsersOffsetLimit($offset, $limit);
            } else if (empty($offset) && empty($limit)) {
                $repository = $this->getDoctrine()->getRepository(User::class);
                $users = $repository->findAll();
            } else {
                return new \Exception('error with offset and limit');
            }
            return $this->render('user/index.html.twig', array(
                'users' => $users, 'loggedIn' => $loggedIn
            ));
        } else {
            $this->addFlash('error', 'you have to login to do this');
            return $this->redirectToRoute('product_index');
        }
    }

    /**
     * returns array with offset/limit user data
     * @param integer $offset
     * @param integer $limit
     * @return array
     */
    private function getUsersOffsetLimit($offset, $limit)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $query = $repository->createQueryBuilder('u')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @Route("/user/create", name="create")
     */
    public function createAction()
    {
        return $this->render('user/create.html.twig');
    }

    /**
     * @Route("/user/store", name="store", methods={"POST"})
     */
    public function storeAction()
    {
        $error = false;

        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm_password = $_POST["confirm_password"];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('error', 'Use a valid e-mail address');
            $error = true;
        }
        if (strlen($password) === 0) {
            $this->addFlash('error', 'Insert a password');
            $error = true;
        }
        if ($password !== $confirm_password) {
            $this->addFlash('error', 'Passwords don´t match');
            $error = true;
        }

        //Check if username is available
        if (!$error) {
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();

            $sql = '
            SELECT username FROM user u
            WHERE username = :username
            ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['username' => $username]);

            $user = $stmt->fetchAll();
            if (!empty($user)) {
                $this->addFlash('error', 'Username is already taken!');
                $error = true;
            }
        }

        //Check if email is unique
        if (!$error) {
            $conn = $this->getDoctrine()->getEntityManager()->getConnection();

            $sql = '
            SELECT email FROM user uroot
            WHERE email = :email
            ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email' => $email]);

            $check_email = $stmt->fetchAll();
            if (!empty($check_email)) {
                $this->addFlash('error', 'E-mail is already taken!');
                $error = true;
            }
        }

        //return error
        if ($error) {
            return $this->render('user/create.html.twig', ['username' => $username, 'email' => $email]);
        } //create User
        else {
            $entityManager = $this->getDoctrine()->getManager();
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPassword(password_hash($password, PASSWORD_DEFAULT));
            $email_verify = bin2hex(random_bytes(16));
            $user->setEmailVerify($email_verify);

            $entityManager->persist($user);
            $entityManager->flush();

            $dotenv = new Dotenv();
            $dotenv->load(__DIR__ . '/.env');

            //send verification email
            # First, instantiate the SDK with your API credentials
            $mg = Mailgun::create(getenv('MAILGUN_APIKEY'));

            # Now, compose and send your message.
            # $mg->messages()->send($domain, $params);
            $mg->messages()->send(getenv('MAILGUN_DOMAIN'), [
                'from' => getenv('MAILFUN_FROM_ADDRESS'),
                'to' => getenv('MY_EMAIL'),
                'subject' => 'Welcome ' . $username . '!',
                'text' => 'http://localhost:8000/registration/email-verify/' . $email_verify
            ]);


            $this->addFlash('success', 'Account successfully created
            Please verify your account now!');
            return $this->redirectToRoute('product_index');
        }
    }

    /**
     * @Route("/registration/email-verify/{verifycode}", methods="GET")
     */
    public function emailVerify($verifycode)
    {
        $conn = $this->getDoctrine()->getEntityManager()->getConnection();

        $sql = '
            SELECT email,email_verify FROM user u
            WHERE email_verify = :email_verify
            ';
        $stmt = $conn->prepare($sql);
        $stmt->execute(['email_verify' => $verifycode]);
        $response = $stmt->fetchAll();

        if (!empty($response)) {

            $sql = '
            UPDATE user SET email_verify="true" 
            WHERE email_verify= :email_verify AND email= :email
            ';
            $stmt = $conn->prepare($sql);
            $stmt->execute(['email_verify' => $verifycode, 'email' => $response[0]['email']]);
            $this->addFlash('success', 'your email is now verified, you can login');
            return $this->redirectToRoute('product_index');
        }
        else{
            return $this->redirectToRoute('product_index');
        }
    }

    /**
     * @Route("/user/edit/{id}", name="edit")
     */
    public function editAction($id)
    {
        $loggedIn = $this->checkLogin();
        if ($loggedIn) {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->find($id);
            $addresses = $em->getRepository(Address::class)->findBy(['user' => $id]);

            return $this->render('user/edit.html.twig', ['user' => $user, 'addresses' => $addresses, 'loggedIn' => $loggedIn]);
        } else {
            $this->addFlash('error', 'you have to login to do this');
            return $this->redirectToRoute('product_index');
        }

    }

    /**
     * @Route("/user/update/{id}", name="update", methods={"PUT"})
     */
    public function updateAction($id, Request $request)
    {
        $loggedIn = $this->checkLogin();
        if ($loggedIn) {
            $params = array();
            $content = $request->getContent();
            if (!empty($content)) {
                $params = json_decode($content, true);
            }

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->find($id);

            $user->setUsername($params["username"]);
            $user->setEmail($params["email"]);
            $user->setPassword($params["password"]);

            $em->flush();

            return $this->json([
                'hello' => 21,
                'name' => 'john'
            ]);
        } else {
            $this->addFlash('error', 'you have to login to do this');
            return $this->redirectToRoute('product_index');
        }
    }

    /**
     * @Route("/user/destroy/{id}", name="destroy", methods={"DELETE"})
     */
    public function destroyAction($id)
    {
        $loggedIn = $this->checkLogin();
        if ($loggedIn) {
            $em = $this->getDoctrine()->getManager();

            $post = $em->getRepository(User::class)->find($id);

            if (!$post) {
                return $this->redirectToRoute('index');
            }

            $em->remove($post);
            $em->flush();

            return $this->redirectToRoute('index');
        } else {
            $this->addFlash('error', 'you have to login to do this');
            return $this->redirectToRoute('product_index');
        }
    }

    /**
     * @Route("/user/address", methods={"GET"})
     */
    public function createAddress()
    {
        $loggedIn = $this->checkLogin();
        if ($loggedIn) {
            return $this->render('address/create.html.twig', array('loggedIn' => $loggedIn));
        } else {
            $this->addFlash('error', 'you have to login to do this');
            return $this->redirectToRoute('product_index');
        }
    }
}