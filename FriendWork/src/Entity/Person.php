<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $familyName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nickName;

    /**
     * @ORM\OneToMany(targetEntity=Relation::class, mappedBy="person1", orphanRemoval=true)
     */
    private $relations1;

    /**
     * @ORM\OneToMany(targetEntity=Relation::class, mappedBy="person2", orphanRemoval=true)
     */
    private $relations2;

    public function __construct()
    {
        $this->relations1 = new ArrayCollection();
        $this->relations2 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function setFamilyName(?string $familyName): self
    {
        $this->familyName = $familyName;

        return $this;
    }

    public function getNickName(): ?string
    {
        return $this->nickName;
    }

    public function setNickName(?string $nickName): self
    {
        $this->nickName = $nickName;

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelations1(): Collection
    {
        return $this->relations1;
    }

    public function addRelation(Relation $relation): self
    {
        if (!$this->relations1->contains($relation)) {
            $this->relations1[] = $relation;
            $relation->setPerson1($this);
        }

        return $this;
    }

    public function removeRelation(Relation $relation): self
    {
        if ($this->relations1->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getPerson1() === $this) {
                $relation->setPerson1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelations2(): Collection
    {
        return $this->relations2;
    }

    public function addRelations2(Relation $relations2): self
    {
        if (!$this->relations2->contains($relations2)) {
            $this->relations2[] = $relations2;
            $relations2->setPerson2($this);
        }

        return $this;
    }

    public function removeRelations2(Relation $relations2): self
    {
        if ($this->relations2->removeElement($relations2)) {
            // set the owning side to null (unless already changed)
            if ($relations2->getPerson2() === $this) {
                $relations2->setPerson2(null);
            }
        }

        return $this;
    }

    public function getRelations()
    {
        return array_merge_recursive($this->relations1, $this->relations2);
    }
}
