<?php

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="admin_comment_index")
     */
    public function index() {
        $repo = $this->getDoctrine()->getRepository(Comment::class);

        $comments = $repo->findAll();

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments
        ]);
    }
}
