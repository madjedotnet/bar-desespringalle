<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Picture;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        $faker = Factory::create('FR-fr');

        for($i = 1 ; $i <= 30 ; $i++) {    
            $album = new Album();
            
            $title = $faker->sentence(5);
            $content = $faker->paragraph(2);
            $albumDate = $faker->dateTime($max = 'now', $timezone = null);
            $creationDate = $faker->dateTime($max = 'now', $timezone = null);

            $album->setTitle($title)
                ->setContent($content)
                ->setCreationUser(mt_rand(1, 7))
                ->setAlbumDate($albumDate)
                ->setCreationDate($creationDate);

            for($j = 1; $j <= mt_rand(2, 25); $j++) {
                $picture = new Picture();

                $iDisposition = mt_rand(0, 2);
                $disposition = "portait";
                if($iDisposition == 0) {
                    $location = $faker->imageUrl($width = 480, $height = 640);
                    $disposition = "portait";
                } else if($iDisposition == 1) {
                    $location = $faker->imageUrl($width = 640, $height = 480);
                    $disposition = "landscape";
                } else {
                    $location = $faker->imageUrl($width = 360, $height = 360);
                    $disposition = "square";
                }

                $caption = $faker->sentence(5);

                $picture->setLocation($location)
                    ->setCaption($caption)
                    ->setDisposition($disposition)
                    ->setAlbum($album);

                $manager->persist($picture);
            }

            $manager->persist($album);
        }
        
        $manager->flush();
    }
}
