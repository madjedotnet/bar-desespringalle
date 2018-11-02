<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Picture;
use App\Form\PictureType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PictureController extends AbstractController
{
    /**
     * @Route("/picture", name="picture")
     */
    public function index()
    {
        return $this->render('picture/index.html.twig', [
            'controller_name' => 'PictureController',
        ]);
    }




    /**
     * Permet de créer une annonce
     * 
     * @Route("/albums/{slug}/add", name="albums_add_pictures")
     * 
     * @return Response
     */
    public function add($slug, Album $album, Request $request, ObjectManager $manager) {
        $album = new Album();
        $picture = new Picture();

        $form = $this->createForm(PictureType::class, $picture);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // debut upload multiple
            $file = $request->files->get['post']['my_file'];
            $uploadDirectory = $this->getParameter('uploadDirectory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $uploadDirectory,
                $filename
            );

            $manager->persist($album);
            $manager->flush();

            // $this->addFlash(
            //     'succes',
            //     "L'album <strong>{$album->getTitle()}</strong> a bien été enregistré !"
            // );

            return $this->redirectToRoute('albums_show', [
                'slug' => $album->getSlug()
            ]);
        }

        return $this->render('picture/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
// /albums/sunt-quis-velit-facere-et-placeat/add