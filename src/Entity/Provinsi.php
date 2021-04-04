<?php

namespace Kematjaya\WilayahBundle\Entity;

use Kematjaya\WilayahBundle\Repository\ProvinsiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProvinsiRepository::class)
 */
class Provinsi
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Kabupaten::class, mappedBy="provinsi", orphanRemoval=true)
     */
    private $kabupatens;

    public function __construct()
    {
        $this->kabupatens = new ArrayCollection();
    }

    public function getId(): ?\Symfony\Component\Uid\Uuid
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Kabupaten[]
     */
    public function getKabupatens(): Collection
    {
        return $this->kabupatens;
    }

    public function addKabupaten(Kabupaten $kabupaten): self
    {
        if (!$this->kabupatens->contains($kabupaten)) {
            $this->kabupatens[] = $kabupaten;
            $kabupaten->setProvinsi($this);
        }

        return $this;
    }

    public function removeKabupaten(Kabupaten $kabupaten): self
    {
        if ($this->kabupatens->removeElement($kabupaten)) {
            // set the owning side to null (unless already changed)
            if ($kabupaten->getProvinsi() === $this) {
                $kabupaten->setProvinsi(null);
            }
        }

        return $this;
    }
}
