<?php

namespace App\Controller\backOffice;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserTypeForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/admin')]
class UserController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em, private UserRepository $userRepository) {}

    #[Route('/user', name: 'user_index')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('backOffice/admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/create', name: 'user_create')]
    public function create(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserTypeForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'User created successfully');

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('backOffice/admin/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/update/{id}', name: 'user_update')]
    public function update(User $user, Request $request): Response
    {
        $form = $this->createForm(UserTypeForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'User updated successfully');

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('backOffice/admin/update.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/delete/{id}', name: 'user_delete')]
    public function delete(User $user, Request $request): Response
    {
        if ($this->isCsrfTokenValid('token' . $user->getId(), $request->request->get('token'))) {
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'User deleted successfully');
        } else {
            $this->addFlash('error', 'Token not valid');
        }

        return $this->redirectToRoute('app_user_index');
    }
}