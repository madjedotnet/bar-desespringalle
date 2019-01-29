<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumLikeRepository")
 */
class AlbumLike
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Album", inversedBy="likes")
     */
    private $album;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="albumLikes")
     */
    private $user;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getAlbum() : ? Album
    {
        return $this->album;
    }

    public function setAlbum(? Album $album) : self
    {
        $this->album = $album;

        return $this;
    }

    public function getUser() : ? User
    {
        return $this->user;
    }

    public function setUser(? User $user) : self
    {
        $this->user = $user;

        return $this;
    }
}
