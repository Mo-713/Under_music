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

    #[Route('/list', name: 'admin_index_article', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $this->articleRepository->findAll(); //permet de trouver tous les articles//

        return $this->render('backOffice/article/index.html.twig', [
            'articles' => $articles, //permet d'afficher tous les articles//
        ]);
    }

    //Création d'un article//
    #[Route('/create', name: 'admin_create_article', methods: ['GET', 'POST'])] //route
    public function create(Request $request): Response //permet de créer un article//
    {
        $article = new Article();

        $form = $this->createForm(ArticleTypeForm::class, $article); //permet l'hydration//
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($article); //permet de persister l'objet//
            $this->em->flush(); //permet la sauvegarde des données transmises//

            $this->addFlash('success', 'Article created successfully'); //ajout d'un message flash//

            return $this->redirectToRoute('admin_index_article'); //redirection ver la page d'accueil//
        }

        return $this->render('backOffice/article/create.html.twig', [
            'form' => $form //permet d'afficher le formulaire//
        ]);
    }

    //Mise à jour d'un article//
    #[Route('/{id}/update', name: 'admin_update_article', methods: ['GET', 'POST'])]
    public function update(Article $article, Request $request): Response
    {
        // $article= $this->articleRepository->find($id);
        $form = $this->createForm(ArticleTypeForm::class, $article); //permet l'hydratation//

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { //permet de valider le formulaire//
            //$this->em->persist($article); //n'est pas nécessaire car l'article est déjà persisté//
            $this->em->flush();

            $this->addFlash('success', 'Article updated successfully');

            return $this->redirectToRoute('admin_index_article');
        }

        return $this->render('backOffice/article/update.html.twig', [
            'form' => $form
        ]);
    }

    //Suppression d'un article//
    #[Route('/{id}/delete', name: 'admin_delete_article', methods: ['POST'])]
    public function delete(Article $article, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('token'))) { //permet de valider le token//
            $this->em->remove($article); //suppression de l'article//
            $this->em->flush();
            $this->addFlash('success', 'Article deleted successfully');
        } else {
            $this->addFlash('error', 'Token not valid');
        }

        return $this->redirectToRoute('admin_index_article');
    }
}
