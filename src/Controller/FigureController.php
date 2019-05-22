<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\Picture;
use App\Form\figure\FigureFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FigureController
 * @package App\Controller
 *
 * @Route("/figure")
 */
class FigureController extends AbstractController
{
    /**
     * @Route("/list")
     * 
     * @Template()
     *
     * @IsGranted("ROLE_ADMIN", message="Vous devez être un utilisateur enregistré.")
     */
    public function list()
    {
        return ['figures' => $this->getDoctrine()
            ->getRepository(Figure::class)
            ->findAll()]
            ;
    }

    /**
     * @param Request $request
     *
     * @return array|Response
     *
     * @Route("/new")
     *
     * @Template()
     *
     * @IsGranted("ROLE_REGISTERED_USER", message="Vous devez être un utilisateur enregistré.")
     */
    public function new(Request $request)
    {
        $figure = new Figure();

        $form = $this->createForm(FigureFormType::class, $figure);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $figure->setUser($this->getUser());
            $figure->setCreatedAt(new \DateTime());
            $entityManager->persist($figure);
            $entityManager->flush();

            $this->addFlash('success', 'La figure a été créée avec succès.');

            return $this->redirectToRoute('app_figure_list');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @param Figure $figure
     *
     * @return array
     *
     * @Route("/view/{id}")
     *
     * @Template()
     */
    public function view(Figure $figure)
    {
        return ['figure' => $figure];
    }

    /**
     * @param Request $request
     * @param $figure
     *
     * @return array|Response
     *
     * @Route("/edit/{id}")
     *
     * @Template()
     *
     * @IsGranted("ROLE_REGISTERED_USER", message="Vous devez être un utilisateur enregistré.")
     */
    public function edit(Request $request, Figure $figure)
    {
        $form = $this->createForm(FigureFormType::class, $figure);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            $this->addFlash('success', 'La figure a été modifiée avec succès.');

            return $this->redirectToRoute('app_figure_list');
        }

        return ['form' => $form->createView(), 'figure' => $figure];
    }

      /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
     * @param $figure
     *
     * @return Response
     *
     * @Route("/delete/{id}")
     *
     * @IsGranted("ROLE_REGISTERED_USER", message="Vous devez être un utilisateur enregistré.")
     */
    public function delete(Figure $figure)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($figure);
        $entityManager->flush();

        $this->addFlash('success', 'La figure a été supprimée avec succès.');

        return $this->redirectToRoute('app_figure_list');
    }
}
