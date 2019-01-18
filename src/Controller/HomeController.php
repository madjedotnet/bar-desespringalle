<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    /**
     * @Route("/", name="homepage")
     *
     */
    public function home(AlbumRepository $albumRepo, UserRepository $userRepo) {

        return $this->render(
            'home.html.twig', [
                'albums' => $albumRepo->findBestAlbums(3),
                'users' => $userRepo->findBestUsers(2)
            ]
        );
    }
}


?>