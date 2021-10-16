<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\Table(name="books")
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    protected string $name;

    /**
     * @ORM\ManyToMany(targetEntity="Author")
     * @ORM\JoinTable(name="authors_books", joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")}, inverseJoinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")})
     */
    protected Collection $authors;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAuthors(): Collection
    {
        return $this->authors;
    }
}
