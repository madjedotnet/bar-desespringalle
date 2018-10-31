<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use App\Form\AlbumType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AlbumController extends AbstractController
{
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
     * 
     * @return Response
     */
    public function create() {
        $album = new Album();

        $form = $this->createForm(AlbumType::class, $album);

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
    public function show($slug, Album $album) 
    {
      //  $album = $repo->findOneBySlug($slug);

        return $this->render('album/show.html.twig', [
            'album' => $album
        ]);
    }
}
