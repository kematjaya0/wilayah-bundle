<?php

namespace Kematjaya\WilayahBundle\Entity;

use Kematjaya\WilayahBundle\Repository\DesaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DesaRepository::class)
 */
class Desa
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
     * @ORM\ManyToOne(targetEntity=Kecamatan::class)
     */
    private $kecamatan;
    
    /**
     * @ORM\ManyToOne(targetEntity=Kelurahan::class, nullable=true)
     */
    private $kelurahan;
    
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

    public function getKecamatan():?Kecamatan 
    {
        return $this->kecamatan;
    }

    public function setKecamatan(?Kecamatan $kecamatan):self 
    {
        $this->kecamatan = $kecamatan;
        
        return $this;
    }

    public function getKelurahan():?Kelurahan 
    {
        return $this->kelurahan;
    }

    public function setKelurahan(?Kelurahan $kelurahan):self 
    {
        $this->kelurahan = $kelurahan;
        
        return $this;
    }
}
