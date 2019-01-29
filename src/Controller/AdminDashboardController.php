<?php

namespace App\Controller;

use App\Service\Statistics;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager, Statistics $statistics)
    {
        $stats = $statistics->getStats();
        $bestAlbums = $statistics->getAlbumsStats('DESC');
        $worstAlbums = $statistics->getAlbumsStats('ASC');

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
            'bestAlbums' => $bestAlbums,
            'worstAlbums' => $worstAlbums
        ]);
    }

    /**
     * 
     */


}
