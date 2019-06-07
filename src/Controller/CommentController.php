<?php

namespace App\Controller;

use App\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller
 *
 * @Route("/comment")
 *
 * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les droits d'accès.")
 */
class CommentController extends AbstractController
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
        return ['comments' => $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findAll()]
            ;
    }

    /**
     * @param Comment $comment
     *
     * @return Response
     *
     * @Route("/delete/{id}")
     */
    public function delete(Comment $comment)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comment);
        $entityManager->flush();

        return $this->redirectToRoute('app_comment_list');
    }

    /**
     * @param int $start
     *
     * @return array
     *
     * @Route("/morecomments/{start}")
     *
     * @Template()
     */
    public function moreComments($start = 10)
    {
        return ['comments' => $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findAll(),
            'start' => $start];
    }
}
