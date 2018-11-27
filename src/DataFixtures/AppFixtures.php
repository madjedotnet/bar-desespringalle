<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Album;
use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture {
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager) {
        $faker = Factory::create('FR-fr');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Matthieu')
            ->setLastName('Bar-Desespringalle')
            ->setEmail('matthieu@bar-desespringalle.fr')
            ->setHash($this->encoder->encodePassword($adminUser, 'password'))
            ->setPicture('https://avatars.io/twitter/_madje')
            ->setIntroduction($faker->sentence())
            ->setDescription($faker->paragraph(2))
            ->addUserRole($adminRole);

        $manager->persist($adminUser);

        // Gestion des users
        $users = [];
        // $genres = ['female', 'male'];

        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            $genre = $faker->numberBetween(1, 2);

            $picture = "https://randomuser.me/api/portraits/";
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            if($genre == 1) {
                $picture .= 'men/' . $pictureId;
            } 
            else {
                $picture .= 'women/' . $pictureId;
            }

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription($faker->paragraph(2))
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        // Gestion des albums
        for($i = 1 ; $i <= 30 ; $i++) {    
            $album = new Album();
            
            $title = $faker->sentence(5);
            $content = $faker->paragraph(2);
            $albumDate = $faker->dateTime($max = 'now', $timezone = null);
            $creationDate = $faker->dateTime($max = 'now', $timezone = null);
            $user = $users[mt_rand(0,count($users) - 1)];

            $album->setTitle($title)
                ->setContent($content)
                ->setAlbumDate($albumDate)
                ->setCreationDate($creationDate)
                ->setAuthor($user);

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
