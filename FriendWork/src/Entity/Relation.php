<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RelationRepository::class)
 */
class Relation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="relations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person1;

    /**
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="relations2")
     * @ORM\JoinColumn(nullable=false)
     */
    private $person2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $positive;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $score;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson1(): ?Person
    {
        return $this->person1;
    }

    public function setPerson1(?Person $person1): self
    {
        $this->person1 = $person1;

        return $this;
    }

    public function getPerson2(): ?Person
    {
        return $this->person2;
    }

    public function setPerson2(?Person $person2): self
    {
        $this->person2 = $person2;

        return $this;
    }

    public function isPositive(): ?bool
    {
        return $this->positive;
    }

    public function setPositive(?bool $positive): self
    {
        $this->positive = $positive;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
