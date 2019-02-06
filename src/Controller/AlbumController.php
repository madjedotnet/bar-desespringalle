<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Comment;
use App\Entity\Picture;
use App\Form\AlbumType;
use App\Form\CommentType;
use App\Repository\AlbumRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AlbumLikeRepository;
use App\Entity\AlbumLike;

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
     * Permet de créer une annonce
     * 
     * @Route("/albums/new", name="albums_create")
     * @IsGranted("ROLE_USER")
     * 
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $album = new Album();

        $form = $this->createForm(AlbumType::class, $album);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->get('album')['photos'];
            $uploadsDirectory = $this->getParameter('uploads_directory');

            foreach($files as $file) 
            {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $uploadsDirectory, 
                    $fileName
                );

                $picture->setAlbum($album);
                $picture->setLocation($fileName);
                $manager->persist($picture);
            }
            
            // foreach ($album->getPictures() as $picture) {
            //     $picture->setAlbum($album);
            //     $manager->persist($picture);
            // }

            $album->setAuthor($this->getUser());

            $manager->persist($album);
            $manager->flush();

            $this->addFlash(
                'succes',
                "L'album <strong>{$album->getTitle()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute('albums_show', [
                'slug' => $album->getSlug()
            ]);
        }

        return $this->render('album/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher un album
     * 
     * @Route("/albums/{slug}", name="albums_show")
     * 
     * @param Album $album
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function show($slug, Album $album, Request $request, ObjectManager $manager)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAlbum($album)
                ->setAuthor($this->getUser());

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a été ajouté..."
            );
        }

        return $this->render('album/show.html.twig', [
            'album' => $album,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition d'album
     * 
     * @Route("/albums/{slug}/edit", name="albums_edit")
     * @Security("is_granted('ROLE_USER') and user === album.getAuthor()", message="Cet album ne vous appartient pas, vous ne pouvez pas le modifier...")
     * 
     * @param Album $album
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function edit(Album $album, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(AlbumType::class, $album);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($album);
            foreach ($album->getPictures() as $picture) {
                $picture->setAlbum($album);
                $manager->persist($picture);
            }

            $manager->persist($album);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'album <strong>{$album->getTitle()}</strong> ont bien été enregistré !"
            );

            return $this->redirectToRoute('albums_show', [
                'slug' => $album->getSlug()
            ]);
        }

        return $this->render('album/edit.html.twig', [
            'form' => $form->createView(),
            'album' => $album
        ]);
    }


    /**
     * Permet de supprimer un album
     * 
     * @Route("/albums/{slug}/delete", name="albums_delete")
     * @Security("is_granted('ROLE_USER') and user == album.getAuthor()", message="Vous n'avez pas le droit d'accèder à cette ressource !")
     * 
     * @param Album $album
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Album $album, ObjectManager $manager)
    {
        $manager->remove($album);
        $manager->flush();

        $this->addFlash(
            'succes',
            "L'album <strong>{$album->getTitle()}</strong> a bien été supprimé !"
        );

        return $this->redirectToRoute("albums_index");
    }

    /**
     * Permet de liker ou unliker un article
     * 
     * @Route("/album/{id}/like", name="album_like")
     *
     * @param Album $album
     * @param ObjectManager $manager
     * @param AlbumLikeRepository $likeRepo
     * @return Response
     */
    public function like(Album $album, ObjectManager $manager, AlbumLikeRepository $likeRepo) : Response
    {
        $user = $this->getUser();

        if (!$user) return $this->json([
            'code' => 403,
            'message' => 'Unauthorized'
        ], 403);

        if ($album->isLikedByUser($user)) {
            $like = $likeRepo->findOneBy([
                'album' => $album,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' => $likeRepo->count([
                    'album' => $album
                ])
            ], 200);
        }

        $like = new AlbumLike();
        $like->setAlbum($album)
            ->setUser($user);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Like ajouté',
            'likes' => $likeRepo->count([
                'album' => $album
            ])
        ], 200);
    }
}
