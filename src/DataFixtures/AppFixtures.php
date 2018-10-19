<?php

namespace App\DataFixtures;

use App\Entity\Album;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1 ; $i <= 30 ; $i++) {    
            $album = new Album();

            $album->setTitle("Titre l'album $i")
                ->setSlug("titre-de-l-album-$i")
                ->setContent("<p>Contenu de l'image</p>")
                ->setCreationUser(mt_rand(1, 7))
                ->setAlbumDate(new \DateTime('2018-09-13'))
                ->setCreationDate(new \DateTime());

            $manager->persist($album);

        }
        
        $manager->flush();
    }
}
