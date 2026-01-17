<?php

namespace Kematjaya\WilayahBundle\Entity;

use Kematjaya\WilayahBundle\Repository\KecamatanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass:KecamatanRepository::class)]
class Kecamatan
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

    #[ORM\ManyToOne(targetEntity: Kabupaten::class, inversedBy: "kecamatans")]
    #[ORM\JoinColumn(nullable: false)]
    private $kabupaten;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function __toString() 
    {
        return $this->getName();
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

    public function getKabupaten(): ?Kabupaten
    {
        return $this->kabupaten;
    }

    public function setKabupaten(?Kabupaten $kabupaten): self
    {
        $this->kabupaten = $kabupaten;

        return $this;
    }
}
