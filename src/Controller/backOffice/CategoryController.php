<?php

namespace App\Controller\backOffice;

use App\Entity\Category;
use App\Form\CategoryTypeForm;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category')] //prefix
class CategoryController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em, private CategoryRepository $categoryRepository){} //injection de dépendance

    #[Route('/list', name: 'admin_index_category', methods: ['GET'])] //route
    public function index(CategoryRepository $categoryRepository): Response
{
  return $this->render('backOffice/categ/index.html.twig', [
    'categories' => $categoryRepository->findAll(),
]);
}

    #[Route('/create', name: 'admin_create_category', methods: ['GET','POST'])]
    public function create(Request $request): Response
    {
        $category = new Category();
        //passe le formulaire et l'entité category pour l'hydrater/ le mapper//
        $form = $this->createForm(CategoryTypeForm::class, $category);

        $form->handleRequest($request); //hydrate la requête. Envoie les données à l'objet request en méthode post//

        //Contrainte de soumission et de validation//
        if ($form->isSubmitted() && $form->isValid()) {
           // $category = $form->getData();
            $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', 'Category created successfully');

            return $this->redirectToRoute('admin_index_category');
        }

        return $this->render('/backOffice/categ/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'admin_update_category', methods: ['GET', 'POST'])]
    public function update(Category $category, Request $request): Response
    {
        $form = $this->createForm(CategoryTypeForm::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$category = $form->getData();
            // $this->em->persist($category);
            $this->em->flush();

            $this->addFlash('success', 'Category updated successfully');

            return $this->redirectToRoute('admin_index_category');
        }

        return $this->render('/backOffice/categ/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_delete_category', methods: ['POST'])]
    public function delete(Category $category, Request $request): Response
    {
        if ($this->isCsrfTokenValid('token' . $category->getId(), $request->request->get('token'))) {
            $this->em->remove($category);
            $this->em->flush();
            $this->addFlash('success', 'Category deleted successfully');
        } else {
            $this->addFlash('error', 'Token not valid');
        }

        return $this->redirectToRoute('admin_index_category');
    }
}