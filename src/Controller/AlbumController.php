<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Family;
use App\Entity\Comment;
use App\Form\AlbumType;
use App\Form\CommentType;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/albums")
 */
class AlbumController extends AbstractController
{
    private $albumRepository;
    private $objectManager;

    public function __construct(AlbumRepository $albumRepository, ObjectManager $objectManager)
    {
        $this->albumRepository = $albumRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @Route("/", name="album_index", methods={"GET"})
     */
    public function index(): Response
    {
        $albums = $this->albumRepository->findAll();

        return $this->render('album/index.html.twig', [
            'albums' => $albums
        ]);
    }

    /**
     * @Route("/new", name="album_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->objectManager->persist($album);
            $this->objectManager->flush();

            return $this->redirectToRoute('album_show', [
                'slug' => $album->getSlug()
            ]);
        }

        return $this->render('album/new.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="album_show")
     * 
     * @param Album $album
     * @param Request $request
     * @return Response
     */
    public function show(Album $album, Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAlbum($album)
                ->setAuthor($this->getUser());

            $this->objectManager->persist($comment);
            $this->objectManager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a été ajouté..."
            );
        }

        return $this->render('album/show.html.twig', [
            'album' => $album,
            'slug' => $album->getSlug(),
            'form' => $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/{slug}/edit", name="album_edit")
     * @Security("is_granted('ROLE_USER') and user === album.getAuthor()", message="Cet album ne vous appartient pas, vous ne pouvez pas le modifier...")
     * 
     */
    public function edit(Request $request, Album $album): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->objectManager->flush();

            $this->addFlash('success', 'Album modifié avec succès');

            return $this->redirectToRoute('album_show', [
                'slug' => $album->getSlug()
            ]);
        }

        return $this->render( 'album/edit.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="album_delete", methods={"DELETE"})
     */
    public function delete(Album $album)
    {
        $this->objectManager->remove($album);
        $this->objectManager->flush();

        $this->addFlash(
            'succes',
            "L'album <strong>{$album->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("album_index");
    }
}
