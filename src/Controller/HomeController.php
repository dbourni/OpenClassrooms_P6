<?php
/**
 *
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    /**
     * @Route("/")
     *
     * @Template("home/home.html.twig")
     */
    public function home()
    {
        return array();
    }

    /**
     * @Route("/backoffice")
     *
     * @Template("home/backoffice.html.twig")
     *
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les droits d'accès.")
     */
    public function backoffice()
    {
        return array();
    }
}
