<?php

namespace App\Controller;

use App\Entity\Media;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminMediaController extends AbstractController {
    /**
     * Permet de supprimer un media
     * 
     * @Route("/{id}/delete", name="admin_media_delete", methods="DELETE")
     * 
     * @param Media $media
     * @param Request $request
     * @return Response
     */
    public function delete(Media $media, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $media->getId(), $data['_token'])) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($media);
            $em->flush();
            return new JsonResponse(['success' => 1]);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
}
