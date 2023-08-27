<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ORM\Table(name: "videos")]
#[ORM\HasLifecycleCallbacks]
class Video
{
    use Timestampable;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Veuillez entrer un titre")]
    #[Assert\Length(min: 3, minMessage: "Votre titre doit avoir minimum 3 caractères")]
    #[Assert\NotEqualTo(value: "merde", message: "Ce mot n'est pas autorisé (m****)")]
    #[Assert\NotEqualTo(value: "wesh", message: "Ce mot n'est pas autorisé (w****)")]
    private ?string $title = null;

    #[ORM\Column(length: 500)]
    private ?string $videoLink = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Veuillez entrer une description")]
    #[Assert\Length(min: 20, minMessage: "Votre titre doit avoir minimum 20 caractères")]
    #[Assert\NotEqualTo(value: "merde", message: "Ce mot n'est pas autorisé (m****)")]
    #[Assert\NotEqualTo(value: "wesh", message: "Ce mot n'est pas autorisé (w****)")]
    private ?string $description = null;
    
    #[ORM\ManyToOne(inversedBy: 'videos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column]
    private ?bool $isPremiumVideo = null;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(string $videoLink): static
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }


    public function isIsPremiumVideo(): ?bool
    {
        return $this->isPremiumVideo;
    }

    public function setIsPremiumVideo(bool $isPremiumVideo): static
    {
        $this->isPremiumVideo = $isPremiumVideo;

        return $this;
    }

}
