<?php

namespace App\Entity;

use App\Repository\WishRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WishRepository::class)]
class Wish
{
    public function __construct()
    {
        $this->published = true;
        $this->dateCreated = new \DateTimeImmutable;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank(message:'Veuillez entrer un titre pour ce souhait'),]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Le titre doit avoir au moins 5 caractères !',
        maxMessage: 'Le titre doit avoir maximum 255 caractères !'
    )]
    #[ORM\Column(length: 255)]
    private ?string $title = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        min: 5,
        max: 5000,
        minMessage: "La description de votre soit doit avoir au moins 5 caractères !",
        maxMessage: "La description de votre soit doit avoir maximum 5000 caractères !"
    )]
    private ?string $description = null;


    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Please provide your username!')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Votre nom ou pseudo doit avoir au moins 3 caractères !',
        maxMessage: 'Votre nom ou pseudo doit avoir au maximum 50 caractère'
    )]
    #[Assert\Regex(
        pattern: '/^[a-z0-9_ -]+$/i',
        message: "Merci d'utiliser seulement des lettres, des chiffres, des underscores et des tirets"
    )]
    private ?string $author = null;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $published = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreated = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateUpdated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filename = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): static
    {
        $this->published = $published;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeImmutable $dateCreated): static
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeImmutable
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?\DateTimeImmutable $dateUpdated): static
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }
}
