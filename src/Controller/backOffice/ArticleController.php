<?php

namespace App\Controller\backOffice;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ArticleTypeForm;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin/article')]
class ArticleController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private ArticleRepository $articleRepository,) {}

    // #[Route('/list', name: 'admin_index_article', methods: ['GET'])]
    // public function index(): Response
    // {
    //     $articles = $this->articleRepository->findAll();

    //     return $this->render('backOffice/article/index.html.twig', [
    //         'articles' => $articles,
    //     ]);
    // }

    #[Route('/create', name: 'admin_create_article', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $article = new Article();

        $form = $this->createForm(ArticleTypeForm::class, $article); //permet l'hydration//
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article);
            $this->em->flush();

            $this->addFlash('success', 'Article created successfully');

            return $this->redirectToRoute('app_admin_index_article');
        }

        return $this->render('backOffice/article/create.html.twig', [
            'form' => $form //variable//
        ]);
    }

    #[Route('/{id}/update', name: 'admin_update_article', methods: ['GET', 'POST'])]
    public function update(Article $article, Request $request): Response
    {
        // $article= $this->articleRepository->find($id);
        $form = $this->createForm(ArticleTypeForm::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$this->em->persist($article);
            $this->em->flush();

            $this->addFlash('success', 'Article updated successfully');

            return $this->redirectToRoute('app_admin_index_article');
        }

        return $this->render('backOffice/article/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_delete_article', methods: ['POST'])]
    public function delete(Article $article, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('token'))) {
            $this->em->remove($article);
            $this->em->flush();
            $this->addFlash('success', 'Article deleted successfully');
        } else {
            $this->addFlash('error', 'Token not valid');
        }

        return $this->redirectToRoute('app_admin_index_article');
    }
}
