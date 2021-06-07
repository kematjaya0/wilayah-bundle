<?php

namespace Kematjaya\WilayahBundle\Entity;

use Kematjaya\WilayahBundle\Repository\KecamatanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KecamatanRepository::class)
 */
class Kecamatan
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator::class)
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
     * @ORM\ManyToOne(targetEntity=Kabupaten::class, inversedBy="kecamatans")
     * @ORM\JoinColumn(nullable=false)
     */
    private $kabupaten;

    public function getId(): ?\Symfony\Component\Uid\Uuid
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
