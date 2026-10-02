<?php

namespace Kematjaya\WilayahBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kematjaya\WilayahBundle\Repository\KelurahanRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: KelurahanRepository::class)]
class Kelurahan implements \Stringable
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

    #[ORM\ManyToOne(targetEntity: Kecamatan::class)]
    private ?Kecamatan $kecamatan = null;

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

    public function getKecamatan(): ?Kecamatan
    {
        return $this->kecamatan;
    }

    public function setKecamatan(?Kecamatan $kecamatan): self
    {
        $this->kecamatan = $kecamatan;

        return $this;
    }
}
