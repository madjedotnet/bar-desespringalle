<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Statistics
{

    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function getStats()
    {
        $users = $this->getUsersCount();
        $albums = $this->getAlbumsCount();
        $comments = $this->getCommentsCount();
        $likes = $this->getLikesCount();

        return compact('users', 'albums', 'comments', 'likes');
    }

    public function getUsersCount()
    {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAlbumsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Album a')->getSingleScalarResult();
    }

    public function getCommentsCount()
    {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    public function getAlbumsStats($direction)
    {
        return $this->manager->createQuery(
            'SELECT COUNT(c.id) AS compte, a.title, a.id, u.firstName, u.picture 
            FROM App\Entity\Comment c 
            JOIN c.album a 
            JOIN a.author u 
            GROUP BY a 
            ORDER BY compte ' . $direction
        )
            ->setMaxResults(5)
            ->getResult();
    }

    public function getLikesCount()
    {
        return $this->manager->createQuery('SELECT COUNT(l) FROM App\Entity\AlbumLike l')->getSingleScalarResult();
    }
}