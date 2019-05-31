<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\user\UserFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 *
 * @Route("/user")
 *
 * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les droits d'accès.")
 */
class UserController extends AbstractController
{
    /**
     * @return array
     *
     * @Route("/list")
     *
     * @Template()
     */
    public function list()
    {
        return ['users' => $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll()]
            ;
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|Response
     *
     * @Route("/edit/{id}")
     *
     * @Template()
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return ['form' => $form->createView(), 'title' => 'Edition d\'un utilisateur'];
    }

    /**
     * @param $id
     *
     * @return Response
     *
     * @Route("/delete/{id}")
     */
    public function delete(User $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($id);
        $entityManager->remove($id);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_list');
    }
}
