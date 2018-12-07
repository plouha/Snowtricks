<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\CommentType;
use Symfony\Component\Validator\Constraints as Assert ;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png", "image/jpg", "image/gif" })
     */
    public $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="relation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="article", orphanRemoval=true, cascade={"persist"})
     */
    private $comments;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="article", orphanRemoval=true, cascade={"persist"})
     */
    private $photos;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="article", orphanRemoval=true, cascade={"persist"})
     */
    private $videos;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCategorie(): ?Category
    {
        return $this->categorie;
    }

    public function setCategorie(?Category $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }
    
   // /**
    // * @return int|null
    // */
    //  public function getId(): ?int
    //  {
    //      return $this->id;
    //  } */
    
    /**
     * @return null|string
     */
    //public function getName(): ?string
    //{
    //    return $this->name;
    //}
    
    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
    
    /**
     * @return Collection
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }
    
    /**
     * @param Photo $photo
     */
    public function addPhoto(Photo $photo)
    {
        $photo->setArticle($this);
        $this->photos->add($photo);
    }
    
    /**
     * @param Photo $photo
     */
    public function removePhoto(Photo $photo)
    {
        $photo->setArticle(null);
        $this->photos->removeElement($photo);
    }
    
    /**
     * @return Collection
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }
    
    /**
     * @param Video $video
     */
    public function addVideo(Video $video)
    {
        $video->setArticle($this);
        $this->videos->add($video);
    }
    
    /**
     * @param Video $video
     */
    public function removeVideo(Video $video)
    {
        $video->setArticle(null);
        $this->videos->removeElement($video);
    }
    
}
