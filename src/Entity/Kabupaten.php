<?php

namespace Kematjaya\WilayahBundle\Entity;

use Kematjaya\WilayahBundle\Repository\KabupatenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass:KabupatenRepository::class)]
class Kabupaten
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code;

    #[ORM\Column(length: 255)]
    private ?string $name;

    #[ORM\ManyToOne(targetEntity: Provinsi::class, inversedBy: "kabupatens")]
    #[ORM\JoinColumn(nullable: false)]
    private $provinsi;

    #[ORM\OneToMany(targetEntity: Kecamatan::class, mappedBy: 'kabupaten', orphanRemoval: true)]
    private $kecamatans;
    
    public function __construct()
    {
        $this->kecamatans = new ArrayCollection();
    }
    
    public function __toString() 
    {
        return $this->getName();
    }

    public function getId(): ?Uuid
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

    public function getProvinsi(): ?Provinsi
    {
        return $this->provinsi;
    }

    public function setProvinsi(?Provinsi $provinsi): self
    {
        $this->provinsi = $provinsi;

        return $this;
    }

    /**
     * @return Collection|Kecamatan[]
     */
    public function getKecamatans(): Collection
    {
        return $this->kecamatans;
    }

    public function addKecamatan(Kecamatan $kecamatan): self
    {
        if (!$this->kecamatans->contains($kecamatan)) {
            $this->kecamatans[] = $kecamatan;
            $kecamatan->setKabupaten($this);
        }

        return $this;
    }

    public function removeKecamatan(Kecamatan $kecamatan): self
    {
        if ($this->kecamatans->removeElement($kecamatan)) {
            // set the owning side to null (unless already changed)
            if ($kecamatan->getKabupaten() === $this) {
                $kecamatan->setKabupaten(null);
            }
        }

        return $this;
    }
}
