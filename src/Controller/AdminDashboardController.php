<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(ObjectManager $manager) {
        $users = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
        $albums = $manager->createQuery('SELECT COUNT(a) FROM App\Entity\Album a')->getSingleScalarResult();
        $comments = $manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();


        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'AdminDashboardController',
            'stats' => compact('users', 'albums', 'comments')
        ]);
    }

    /**
     * 
     */


}
