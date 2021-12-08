<?php

namespace App\Entity;

use App\Repository\BlogOptionsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogOptionsRepository::class)
 */
class BlogOptions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastEdit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datakey;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getLastEdit(): ?\DateTimeInterface
    {
        return $this->lastEdit;
    }

    public function setLastEdit(?\DateTimeInterface $lastEdit): self
    {
        $this->lastEdit = $lastEdit;

        return $this;
    }

    public function getDatakey(): ?string
    {
        return $this->datakey;
    }

    public function setDatakey(string $datakey): self
    {
        $this->datakey = $datakey;

        return $this;
    }
}
