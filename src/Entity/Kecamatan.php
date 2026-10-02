<?php

namespace Kematjaya\WilayahBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kematjaya\WilayahBundle\Repository\KecamatanRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: KecamatanRepository::class)]
class Kecamatan implements \Stringable
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Kabupaten::class, inversedBy: "kecamatans")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Kabupaten $kabupaten = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->getName();
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
