<?php

namespace App\Controller\frontOffice;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
class ArticleController extends AbstractController
{
    public function __construct(private ArticleRepository $articleRepository) {}

    #[Route('/list', name: 'app_listArticles')]
    public function index(): Response
    {
        return $this->render('frontOffice/article/index.html.twig', [
            'articles' => $this->articleRepository->findAll(),
        ]);
    }

    #[Route('/show/{id}', name: 'app_article')]
    public function show(Article $article): Response
    {
        $articleByCateg = $this -> articleRepository -> findThreeByCategory($article->getCategory(), $article->getId());
        return $this->render('frontOffice/article/show.html.twig', [
            'article' => $article,
            'articleByCateg' => $articleByCateg
        ]);
    }
}

