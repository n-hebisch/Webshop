<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Niklas;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Tests\Fixtures\ToString;

/**
 * Class LuckyController
 * @package AppBundle\Controller
 * @Route("/lucky")
 */
class LuckyController extends Controller
{
    /**
     * @Route("/number")
     */
    public function numberAction()
    {
        $number = random_int(0, 100);

//        return new Response(
//            '<html><body>Lucky number: '.$number.'</body></html>'
//        );
        return $this->render('lucky/number.html.twig', array(
            'number' => $number,
        ));
    }

}

