<?php

namespace App\Controller;

use App\Entity\Figure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Displays the homepage
     *
     * @return array
     *
     * @Route("/")
     *
     * @Template()
     */
    public function home()
    {
        return ['figures' => $this->getDoctrine()
            ->getRepository(Figure::class)
            ->findAll()]
            ;
    }

    /**
     * Displays more figures on the homepage
     *
     * @param int $start
     *
     * @return array
     *
     * @Route("/morefigures/{start}")
     *
     * @Template()
     */
    public function moreFigures($start = 5)
    {
        return ['figures' => $this->getDoctrine()
            ->getRepository(Figure::class)
            ->findAll(),
            'start' => $start]
            ;
    }

    /**
     * Displays the backoffice
     *
     * @return array
     *
     * @Route("/backoffice")
     *
     * @Template()
     *
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les droits d'accès.")
     */
    public function backoffice()
    {
        return [];
    }
}
