<?php

/**
 * This file is part of the wilayah-bundle.
 */

namespace Kematjaya\WilayahBundle\Entity;

use Kematjaya\WilayahBundle\Repository\KelurahanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KelurahanRepository::class)
 */
class Kelurahan 
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
     * @ORM\ManyToOne(targetEntity=Kecamatan::class)
     */
    private $kecamatan;
    
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
