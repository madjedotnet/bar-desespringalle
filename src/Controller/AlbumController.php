<?php

namespace App\Controller;

use App\Entity\Album;
use App\Repository\AlbumRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
