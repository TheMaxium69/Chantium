<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * @ORM\OneToOne(targetEntity=Project::class, mappedBy="image", cascade={"persist", "remove"})
     */
    private $project;

    /**
     * @ORM\OneToOne(targetEntity=Card::class, mappedBy="image", cascade={"persist", "remove"})
     */
    private $card;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): self
    {
        // unset the owning side of the relation if necessary
        if ($project === null && $this->project !== null) {
            $this->project->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($project !== null && $project->getImage() !== $this) {
            $project->setImage($this);
        }

        $this->project = $project;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        // unset the owning side of the relation if necessary
        if ($card === null && $this->card !== null) {
            $this->card->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($card !== null && $card->getImage() !== $this) {
            $card->setImage($this);
        }

        $this->card = $card;

        return $this;
    }
}
