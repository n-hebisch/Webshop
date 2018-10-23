<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 26.09.18
 * Time: 10:34
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/category" ,methods={"GET"})
     */
    public function getCategoryAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $categoriesArray = [];
        foreach ($categories as $category) {

            $categoriesArray[] = ['id' => $category->getId(), 'category_name' => $category->getCategoryName()];
        }
        $a = json_encode($categoriesArray, true);

        return new Response($a);
    }
}