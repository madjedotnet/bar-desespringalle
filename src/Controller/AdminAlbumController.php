<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Media;
use App\Form\AlbumType;
use App\Service\Paginator;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAlbumController extends AbstractController {
    /**
     * @Route("/admin/albums/{page<\d+>?1}", name="admin_albums_index")
     */
    public function index(AlbumRepository $repo, $page, Paginator $paginator) {
        $paginator->setEntityClass(Album::class)
            ->setPage($page)
            ->setLimit(20);

        return $this->render('admin/album/index.html.twig', [
            'paginator' => $paginator
        ]);
    }

    /**
     * Permet d'éditer un album photo depuis l'administration
     *
     * @Route("/admin/albums/{id}/edit", name="admin_albums_edit")
     * 
     * @param Album $album
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
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

    /**
     * Permet de supprimer un album
     * 
     * @Route("/admin/albums/{id}/delete", name="admin_albums_delete")
     * 
     * @param Album $album
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Album $album, ObjectManager $manager) {
        if(count($album->getComments()) > 0 || count($album->getMedias()) > 0)  {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer un album qui a des photos, vidéos ou des commentaires !"
            );
        } else {
            $manager->remove($album);
            $manager->flush($album);

            $this->addFlash(
                'success',
                "L'annonce {$album->getTitle()} a bien été supprimée !"
            );
        }

        return $this->redirectToRoute('admin_albums_index');
    }
}
