<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\security\LoginFormType;
use App\Form\security\NewPasswordFormType;
use App\Form\security\RegistrationFormType;
use App\Form\security\ResetPasswordFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityController
 * @package App\Controller
 */
class SecurityController extends AbstractController
{
    /**
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return array
     *
     * @Route("/login")
     *
     * @Template()
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $defaultData = [];
        $form = $this->createForm(LoginFormType::class, $defaultData);
        $form->handleRequest($request);

        return ['loginForm' => $form->createView(), 'last_username' => $lastUsername, 'error' => $error,];
    }

    /**
     * @Route("/logout", methods={"GET"})
     */
    public function logout()
    {

    }

    /**
     * User registration
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param \Swift_Mailer $mailer
     *
     * @return array | Response
     *
     * @Route("/register")
     *
     * @Template()
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setInitCode(md5(uniqid('', true)));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Send an email for validation
            $message = (new \Swift_Message('Confirmation de votre inscription'))
                ->setFrom('dbourni@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/registration.html.twig',
                        ['email' => $user->getEmail(), 'initCode' => $user->getInitCode()]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            return $this->redirectToRoute('app_security_login');
        }

        return ['registrationForm' => $form->createView()];
    }

    /**
     * Validate a user by clicking on a link in the received email
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/registerValidation")
     */
    public function registerValidation(Request $request)
    {
        $userToValidate = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['initCode' => $request->get('initCode')]);

        if ($userToValidate) {
            $userToValidate->setRoles(['ROLE_REGISTERED_USER']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userToValidate);
            $entityManager->flush();

            return $this->redirectToRoute('app_security_login');
        }

        return $this->redirectToRoute('app_home_home');
    }

    /**
     * Reset the password
     *
     * @param Request $request
     * @param \Swift_Mailer $mailer
     *
     * @return array | Response
     *
     * @Route("/resetPassword")
     *
     * @Template()
     */
    public function resetPassword(Request $request, \Swift_Mailer $mailer)
    {
        $defaultData = [];
        $form = $this->createForm(ResetPasswordFormType::class, $defaultData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['email' => $data['email']]);

            if ($user) {
                $user->setInitCode(md5(uniqid('', true)));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // Send an email for validation
                $message = (new \Swift_Message('Réinitialisation du mot de passe'))
                    ->setFrom('dbourni@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'emails/resetPassword.html.twig',
                            ['initCode' => $user->getInitCode()]
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                return $this->redirectToRoute('app_security_login');
            }

            return $this->redirectToRoute('app_security_login');
        }

        return ['resetPasswordForm' => $form->createView()];
    }

    /**
     * Set the new passwords
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return array | Response
     *
     * @Route("/newPassword")
     *
     * @Template()
     */
    public function newPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $defaultData = [];
        $error = '';
        $form = $this->createForm(NewPasswordFormType::class, $defaultData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->findOneBy(['initCode' => $request->get('initCode')]);

            if ($user and $data['password'] == $data['password2']) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $data['password']
                    )
                );
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_security_login');
            }

            $error = 'Le code de sécurité ou le mot de passe est erroné !';
        }

        return ['newPasswordForm' => $form->createView(), 'error' => $error,];
    }
}
