<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Picture;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlbumController extends AbstractController {
    /**
     * @Route("/albums", name="albums_index")
     */
    public function index(AlbumRepository $repo) 
    {
        $albums = $repo->findAll();

        return $this->render('album/index.html.twig', [
            'albums' => $albums,
        ]);
    }

    /**
     * Permet de créer une annonce
     * 
     * @Route("/albums/new", name="albums_create")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager) {
        $album = new Album();

        $form = $this->createForm(AlbumType::class, $album);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            foreach($album->getPictures() as $picture) {
                $picture->setAlbum($album);
                $manager->persist($picture);
            }

            $album->setAuthor($this->getUser());

            $manager->persist($album);
            $manager->flush();

            $this->addFlash(
                'succes',
                "L'album <strong>{$album->getTitle()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('albums_show', [
                'slug' => $album->getSlug()
            ]);
        }

        return $this->render('album/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher un album
     * 
     * @Route("/albums/{slug}", name="albums_show")
     * 
     * @return Response
     */
    public function show($slug, Album $album) {
        return $this->render('album/show.html.twig', [
            'album' => $album
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition d'album
     * 
     * @Route("/albums/{slug}/edit", name="albums_edit")
     * @Security("is_granted('ROLE_USER') and user === album.getAuthor()", message="Cette album ne vous appartient pas, vous ne pouvez pas le modifier...")
     *
     * @return Response
     */
    public function edit(Album $album, Request $request, ObjectManager $manager) {
        $form = $this->createForm(AlbumType::class, $album);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            foreach($album->getPictures() as $picture) {
                $picture->setAlbum($album);
                $manager->persist($picture);
            }

            $manager->persist($album);
            $manager->flush();

            $this->addFlash(
                'succes',
                "Les modifications de l'album <strong>{$album->getTitle()}</strong> ont bien été enregistré !"
            );

            return $this->redirectToRoute('albums_show', [
                'slug' => $album->getSlug()
            ]);
        }

        return $this->render('album/edit.html.twig', [
            'form' => $form->createView(),
            'album' => $album
        ]);
    }
}
