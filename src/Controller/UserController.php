<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Form\PasswordProfileType;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class UserController extends AbstractController
{
    /**
     * @Route("/profile/delete", name="delete_profile", methods={"POST"})
     */
    public function delete_profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/profile/editPassword", name="edit_profile_password", methods={"GET", "POST"})
     */
    public function edituserpassword(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(PasswordProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/profile_edit_password.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/edit", name="edit_profile", methods={"GET" , "POST" })
     */
    public function edit_profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/profile_edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/profile", name="profile", methods={"GET"})
     */
    public function profile(UserRepository $userRepository): Response
    {

        return $this->render('user/profile.html.twig', [
            'user' => $userRepository->findOneBy(['id' => $this->getUser()->getId()]),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/user/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/user/new", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/user/{id}/editusernp", name="user_edit", methods={"GET", "POST"})
     */
    public function editusernp(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/user/delete/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
