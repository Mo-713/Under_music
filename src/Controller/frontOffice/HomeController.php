<?php

namespace App\Controller\frontOffice;


use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_home')]
    public function index(ArticleRepository $er): Response
    {
        $articles = $er->findBy([], ['createdAt' => 'DESC'], 6, 0);
        
        return $this->render('home/index.html.twig', [
            'articles' => $articles
        ]);
    }
}
