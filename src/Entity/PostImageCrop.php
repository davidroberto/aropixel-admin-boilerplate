<?php

declare(strict_types=1);

namespace App\Entity;

use Aropixel\AdminBundle\Entity\Crop;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostImageCropRepository")
 */
class PostImageCrop extends Crop
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="PostImage", inversedBy="crops")
     */
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?PostImage
    {
        return $this->image;
    }

    public function setImage(?PostImage $image): self
    {
        $this->image = $image;

        return $this;
    }
}
