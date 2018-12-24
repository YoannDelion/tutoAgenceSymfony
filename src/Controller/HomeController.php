<?php
/**
 * User: YoannD
 * Date: 19/12/2018
 * Time: 11:55
 */

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @param PropertyRepository $repository
     */
    public function index(PropertyRepository $repository):Response{

        $properties = $repository->findLatest();

        return $this->render('pages/home.html.twig',['properties' => $properties]);
    }
}