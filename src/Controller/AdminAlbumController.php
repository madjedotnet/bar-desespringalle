<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAlbumController extends AbstractController
{
    /**
     * @Route("/admin/albums", name="admin_albums_index")
     */
    public function index(AlbumRepository $repo)
    {
        return $this->render('admin/album/index.html.twig', [
            'albums' => $repo->findAll()
        ]);
    }

    /**
     * Permet d'éditer un album photo depuis l'administration
     *
     * @Route("/admin/albums/{id}/edit", name="admin_albums_edit")
     * 
     * @param Album $album
     * @return void
     */
    public function edit(Album $album, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AlbumType::class, $album);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($album);
            $manager->flush();

            $this->addFlash('success', 
                "L'album <strong>{$album->getTitle()}</strong> a bien été modifié !"
            );
        }

        return $this->render("admin/album/edit.html.twig", [
            'album' => $album,
            'form' => $form->createView()
        ]);
    }
}
