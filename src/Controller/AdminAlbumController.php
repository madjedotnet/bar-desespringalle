<?php

namespace App\Controller;

use App\Repository\AlbumRepository;
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
}
