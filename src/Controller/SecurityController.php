<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/denied_access", name="denied_access")
     */
    public function index(): Response
    {
        return $this->render('security/login_denied.html.twig');
    }

    /**
     * @Route("/forgotten-password", name="app_forgotten_password")
     */
    public function forgottenPass(Request $request, UserRepository $userRepo, TokenGeneratorInterface $tokenGenerator, MailerInterface $mailer): Response
    {
        // create form to retrieve email
        $form = $this->createForm(ResetPassType::class);

        //trait form
        $form->handleRequest($request);


        //if form is valid
        if ( $form->isSubmitted() && $form->isValid()){
            //extract data of the email to reset the password of
            $data = $form->getData();
            //search if the user exist with that email
            $user = $userRepo->findOneBy([
                'email' => $data['email']
            ]);
            //if user doesn't exist
            if ( !$user ){
                // we send a flash message
                $this->addFlash('danger' , 'this email doesnt exist');
                return $this->redirectToRoute('app_login');
            }

            // if user exist we generate a token
            $token = $tokenGenerator->generateToken();
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }catch(\Exception $e){
                $this->addFlash('warning' , 'an error occured : ' . $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // generate url or resetting the password
            $url = $this->generateUrl('app_reset_password' , ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new TemplatedEmail())
                ->from('adrenaline@gmail.com')
                ->to($user->getEmail())
                ->subject('PASSWORD RESET')
                ->htmlTemplate('security/reset_email.html.twig')
                ->context([
                    'fullname' => $user->getFirstname() . " " .$user->getLastname(),
                    'url' => $url,
                ]);

            $mailer->send($email);


            //flash message of email sent confirmation
            $this->addFlash('message', 'The reset Password email has been sent');
            return $this->redirectToRoute('app_login');
        }
        // send to email request page
        return $this->render('security/forgotten_password.html.twig', ['emailForm' => $form->createView()]);
    }

    /**
     * @Route("/resetPassword/{token}", name="app_reset_password")
     */
    public function verifyUserEmail($token,Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // search for user with the token
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' =>$token]);

        if ( !$user ){
            $this->addFlash('danger' , 'token not existing');
            return $this->redirectToRoute('app_login');
        }

        if ( $request->isMethod('POST')){
            $Password = $request->request->get('password');
            $Password2 = $request->request->get('password2');
            $error = "PASSWORD DOESNT MATCH ";
            if ( $Password != $Password2){
                return $this->render('security/reset_password.html.twig', [
                    'token' => $token,
                    'error' => $error
                ]);
            }

            //delete token of the user
            $user->setResetToken(null);
            //encode password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $request->request->get('password')
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message' , 'password has been updated successfully');
            return $this->redirectToRoute('app_login');
        }
        else{
            return $this->render('security/reset_password.html.twig', [
                'token' => $token,
                'error' => ""
            ]);
        }
    }

    /**
     * @Route("/choice", name="choice")
     */
    public function choice(){

        $full_name = $this->getUser()->getFirstname() . " " . $this->getUser()->getLastname();
        return $this->render('security/choice.html.twig', [
            'name' => $full_name,
        ]);
    }
}
