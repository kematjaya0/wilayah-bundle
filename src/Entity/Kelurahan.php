<?php

namespace Kematjaya\WilayahBundle\Entity;

use Kematjaya\WilayahBundle\Repository\KelurahanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass:KelurahanRepository::class)]
class Kelurahan 
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

    #[ORM\ManyToOne(targetEntity: Kecamatan::class)]
    private $kecamatan;
    
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

    public function getKecamatan():?Kecamatan 
    {
        return $this->kecamatan;
    }

    public function setKecamatan(?Kecamatan $kecamatan):self 
    {
        $this->kecamatan = $kecamatan;
        
        return $this;
    }
}
