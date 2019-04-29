<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    /**
     * @Route("/")
     *
     * @Template()
     */
    public function home()
    {
        return [];
    }

    /**
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
