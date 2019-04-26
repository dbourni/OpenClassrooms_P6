<?php
/**
 *
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\user\EditUserFormType;
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
 * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les droits d'accès.")
 */
class UserController extends AbstractController
{
    /**
     * @return array
     *
     * @Route("/userlist")
     *
     * @Template("user/list.html.twig")
     */
    public function list()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return array('users' => $users);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return array|Response
     *
     * @Route("/useredit/{id}")
     *
     * @Template("user/edit.html.twig")
     */
    public function edit(Request $request, $id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $form = $this->createForm(EditUserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_list');
        }

        return array('form' => $form->createView(), 'title' => 'Edition d\'un utilisateur');
    }

    /**
     * @param $id
     *
     * @return Response
     *
     * @Route("/userdelete/{id}")
     */
    public function delete($id)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_list');
    }
}
