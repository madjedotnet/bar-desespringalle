<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
// validation du formulaire
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *  fields={"email"}, 
 *  message="Cette adresse mail est déjà utilisée"
 * )
 */
class User implements UserInterface {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entrer votre nom de famille !")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez entrer votre prénom !")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez entrer une adresse mail valide !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url(message="Url de votre photo de profil incorrecte !")
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Les mots de passe saisis sont différents !")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $introduction;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Album", mappedBy="author")
     */
    private $albums;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AlbumLike", mappedBy="user")
     */
    private $albumLikes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Album", mappedBy="users")
     */
    private $inAlbums;

    public function __construct()
    {
        $this->albums = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->albumLikes = new ArrayCollection();
        $this->inAlbums = new ArrayCollection();
    }

    public function getFullName() {
    return "{$this->firstName} {$this->lastName}" ;
    }
    /**
     * Permet d'initialiser le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName . ' ' . $this->lastName);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Album[]
     */
    public function getAlbums(): Collection
    {
        return $this->albums;
    }

    public function addAlbum(Album $album): self
    {
        if (!$this->albums->contains($album)) {
            $this->albums[] = $album;
            $album->setAuthor($this);
        }

        return $this;
    }

    public function removeAlbum(Album $album): self
    {
        if ($this->albums->contains($album)) {
            $this->albums->removeElement($album);
            // set the owning side to null (unless already changed)
            if ($album->getAuthor() === $this) {
                $album->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles() {
        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();
        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword() {
        return $this->hash;
    }

    public function getSalt() {

    }

    public function getUsername() {
        return $this->email;
    }

    public function eraseCredentials() {

    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AlbumLike[]
     */
    public function getAlbumLikes(): Collection
    {
        return $this->albumLikes;
    }

    public function addAlbumLike(AlbumLike $albumLike): self
    {
        if (!$this->albumLikes->contains($albumLike)) {
            $this->albumLikes[] = $albumLike;
            $albumLike->setUser($this);
        }

        return $this;
    }

    public function removeAlbumLike(AlbumLike $albumLike): self
    {
        if ($this->albumLikes->contains($albumLike)) {
            $this->albumLikes->removeElement($albumLike);
            // set the owning side to null (unless already changed)
            if ($albumLike->getUser() === $this) {
                $albumLike->setUser(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->firstName;
    }

    /**
     * @return Collection|Album[]
     */
    public function getInAlbums(): Collection
    {
        return $this->inAlbums;
    }

    public function addInAlbum(Album $inAlbum): self
    {
        if (!$this->inAlbums->contains($inAlbum)) {
            $this->inAlbums[] = $inAlbum;
            $inAlbum->addUser($this);
        }

        return $this;
    }

    public function removeInAlbum(Album $inAlbum): self
    {
        if ($this->inAlbums->contains($inAlbum)) {
            $this->inAlbums->removeElement($inAlbum);
            $inAlbum->removeUser($this);
        }

        return $this;
    }
}
