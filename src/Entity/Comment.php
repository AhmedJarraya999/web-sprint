<?php

namespace App\Entity;
use App\Repository\CommentRepository;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="id_exp", columns={"id_exp"}), @ORM\Index(name="author", columns={"author"})})
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
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
     *
     * @ORM\Column(name="author", type="integer", nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255, nullable=false)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="likes", type="integer")
     */
    private $likes;

    /**
     * @var \Experience
     *
     * @ORM\ManyToOne(targetEntity="Experience" , inversedBy="comments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_exp", referencedColumnName="id")
     * })
     */
    private $idExp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?int
    {
        return $this->author;
    }

    public function setAuthor(int $author): self
    {
        $this->author = $author;

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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;
        $this->date = $date;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;
        $this->likes = 0;

        return $this;
    }

    public function getIdExp(): ?Experience
    {
        return $this->idExp;
    }

    public function setIdExp(?Experience $idExp): self
    {
        $this->idExp = $idExp;

        return $this;
    }
    public function __toString()
    {
        return(string)$this->getIdExp();
    }

}
