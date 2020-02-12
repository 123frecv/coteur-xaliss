<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\ContratRepository")
 */
class Contrat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_create;

    /**
     * @ORM\Column(type="text")
     */
    private $treme;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Partenaire", inversedBy="contrats")
     */
    private $partenaines;

    public function __construct()
    {
        $this->partenaines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getTreme(): ?string
    {
        return $this->treme;
    }

    public function setTreme(string $treme): self
    {
        $this->treme = $treme;

        return $this;
    }

    /**
     * @return Collection|Partenaire[]
     */
    public function getPartenaines(): Collection
    {
        return $this->partenaines;
    }

    public function addPartenaine(Partenaire $partenaine): self
    {
        if (!$this->partenaines->contains($partenaine)) {
            $this->partenaines[] = $partenaine;
        }

        return $this;
    }

    public function removePartenaine(Partenaire $partenaine): self
    {
        if ($this->partenaines->contains($partenaine)) {
            $this->partenaines->removeElement($partenaine);
        }

        return $this;
    }
}
