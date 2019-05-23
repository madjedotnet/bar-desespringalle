<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Media;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
// validation du formulaire
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AlbumRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *      fields={"title"},
 *      message="Un album a déjà ce nom, veuillez le changer..."
 * )
 */
class Album
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10, max=255, minMessage="Le titre doit faire plus de 10 caractères !", maxMessage="Le titre ne doit pas faire plus de 255 caractères !")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $albumDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="album", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AlbumLike", mappedBy="album", orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Media", mappedBy="album", orphanRemoval=true, cascade={"persist"})
     */
    private $medias;

    /**
     * @Assert\All({
     *   @Assert\Image(mimeTypes="image/jpeg")
     * })
     */
    private $mediaFiles;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->medias = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getTitle() : ? string
    {
        return $this->title;
    }

    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug() : ? string
    {
        return $this->slug;
    }

    public function setSlug(string $slug) : self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent() : ? string
    {
        return $this->content;
    }

    public function setContent(string $content) : self
    {
        $this->content = $content;

        return $this;
    }

    public function getAlbumDate() : ? \DateTimeInterface
    {
        return $this->albumDate;
    }

    public function setAlbumDate(\DateTimeInterface $albumDate) : self
    {
        $this->albumDate = $albumDate;

        return $this;
    }

    public function getCreationDate() : ? \DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate) : self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getAuthor() : ? User
    {
        return $this->author;
    }

    public function setAuthor(? User $author) : self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments() : Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment) : self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAlbum($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment) : self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAlbum() === $this) {
                $comment->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AlbumLike[]
     */
    public function getLikes() : Collection
    {
        return $this->likes;
    }

    public function addLike(AlbumLike $like) : self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setAlbum($this);
        }

        return $this;
    }

    public function removeLike(AlbumLike $like) : self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getAlbum() === $this) {
                $like->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * Permet de savoir si l'utilisateur aime un album
     *
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user) : bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function getMedia(): ?Media
    {
        if ($this->medias->isEmpty()) {
            return null;
        }
        else {
            return $this-> medias->first();
        }
    }

    public function addMedia(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setAlbum($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->contains($media)) {
            $this->medias->removeElement($media);
            // set the owning side to null (unless already changed)
            if ($media->getAlbum() === $this) {
                $media->setAlbum(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }

    /**
     * @param mixed $mediaFiles
     * @return Album
     */
    public function setMediaFiles($mediaFiles): self
    {
        foreach($mediaFiles as $mediaFile) {
            $media = new Media();
            $media->setMediaFile($mediaFile);
            $this->addMedia($media);
        }

        $this->mediaFiles = $mediaFiles;
        return $this;
    }
}
