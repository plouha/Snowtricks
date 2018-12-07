<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 */
class Photo
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    public $file;
    
    /**
     * @var Article|null
     * @ORM\ManyToOne(targetEntity="Article", inversedBy="photos")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $article;
    
    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }
    
    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }
    
    /**
     * @return Article|null
     */
    public function getArticle(): ?Article
    {
        return $this->article;
    }
    
    /**
     * @param Article|null $article
     */
    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }
}