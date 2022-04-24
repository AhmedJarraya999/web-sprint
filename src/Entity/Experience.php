<?php

namespace App\Entity;
use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;
use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;

/**
 * Experience
 *
 * @ORM\Table(name="experience", indexes={@ORM\Index(name="id_author", columns={"id_author"})})
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 *
 */
class Experience
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="id_author", type="integer", nullable=false)
     */
    private $idAuthor;

    /**
     * @var string
     *@Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @ProfanityAssert\ProfanityCheck
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=65535, nullable=false)
     * @Assert\NotBlank()
     * @ProfanityAssert\ProfanityCheck
     */
    private $content;

    /**
     * @var int
     *
     * @ORM\Column(name="likes", type="integer", nullable=false)
     *
     */
    private $likes;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255, nullable=false)
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="experience", orphanRemoval=true)
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAuthor(): ?int
    {
        return $this->idAuthor;
    }

    public function setIdAuthor(int $idAuthor): self
    {
        $this->idAuthor = $idAuthor;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

      /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addSComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setIdExp($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getIdExp() === $this) {
                $comment->setIdExp(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getId();
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setExperience($this);
        }

        return $this;
    }

}
