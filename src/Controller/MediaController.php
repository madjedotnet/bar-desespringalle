<?php
namespace App\Controller;

use App\Entity\Media;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/media")
 */
class MediaController extends AbstractController {
    /**
     * @Route("/{id}", name="media_delete", methods="DELETE")
     */
    public function delete(Media $media, Request $request, ObjectManager $manager) {
        $data = json_decode($request->getContent(), true);
        if($this->isCsrfTokenValid('delete' . $media->getId(), $data['_token'])) {
            $manager->remove($media);
            $manager->flush();
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token invalide !', 400]);
        }
    }
}