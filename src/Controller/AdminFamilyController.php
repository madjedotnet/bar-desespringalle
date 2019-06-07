<?php

namespace App\Controller;

use App\Entity\Family;
use App\Form\AdminFamilyType;
use App\Repository\FamilyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/family")
 */
class AdminFamilyController extends AbstractController
{
    /**
     * @Route("/", name="family_index", methods={"GET"})
     */
    public function index(FamilyRepository $familyRepository): Response
    {
        return $this->render('admin/family/index.html.twig', [
            'families' => $familyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="family_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $family = new Family();
        $form = $this->createForm(AdminFamilyType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($family);
            $entityManager->flush();

            return $this->redirectToRoute('family_index');
        }

        return $this->render( 'admin/family/new.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="family_show", methods={"GET"})
     */
    public function show(Family $family): Response
    {
        return $this->render( 'admin/family/show.html.twig', [
            'family' => $family,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="family_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Family $family): Response
    {
        $form = $this->createForm(AdminFamilyType::class, $family);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('family_index', [
                'id' => $family->getId(),
            ]);
        }

        return $this->render( 'admin/family/edit.html.twig', [
            'family' => $family,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="family_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Family $family): Response
    {
        if ($this->isCsrfTokenValid('delete'.$family->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($family);
            $entityManager->flush();
        }

        return $this->redirectToRoute('family_index');
    }
}
