<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $article = null;

    #[ORM\ManyToOne(inversedBy: 'lesarticles', fetch:"EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $id_Users = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'id_article', fetch : "EAGER",orphanRemoval: true,targetEntity: Comments::class)]
    private Collection $Lescommentaires;

    public function __construct()
    {
        $this->Lescommentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getId_Users(): ?Users
    {
        return $this->id_Users;
    }

    public function setId_Users(Users $id_Users): self
    {
        $this->id_Users = $id_Users;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getLescommentaires(): Collection
    {
        return $this->Lescommentaires;
    }

    public function addLescommentaire(Comments $lescommentaire): self
    {
        if (!$this->Lescommentaires->contains($lescommentaire)) {
            $this->Lescommentaires->add($lescommentaire);
            $lescommentaire->setIdArticle($this);
        }

        return $this;
    }

    public function removeLescommentaire(Comments $lescommentaire): self
    {
        if ($this->Lescommentaires->removeElement($lescommentaire)) {
            // set the owning side to null (unless already changed)
            if ($lescommentaire->getIdArticle() === $this) {
                $lescommentaire->setIdArticle(null);
            }
        }

        return $this;
    }
}
