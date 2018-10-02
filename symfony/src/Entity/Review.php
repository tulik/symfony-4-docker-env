<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\IdColumnTrait;
use App\Traits\TimeAwareTrait;
use App\Traits\UserOwnableResourceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 *
 * @ORM\Table(name="review")
 */
class Review
{
    use IdColumnTrait;
    use TimeAwareTrait;
    use UserOwnableResourceTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     */
    protected $body;

    /**
     * @var ArrayCollection|Book[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Book")
     *
     * @Assert\NotBlank
     *
     * @JMS\Groups({"books"})
     */
    protected $books;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     */
    protected $rating;

    /**
     * @var User
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank
     */
    protected $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank
     */
    protected $publicationDate;

    /**
     * Review constructor.
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param ArrayCollection $books
     */
    public function setBooks(ArrayCollection $books): void
    {
        $this->books = $books;
    }

    /**
     * @return string
     */
    public function getBooks(): string
    {
        return $this->books;
    }

    /**
     * @param string $rating
     */
    public function setRating(string $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param \DateTime $publicationDate
     */
    public function setPublicationDate(\DateTime $publicationDate): void
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return \DateTime
     */
    public function getPublicationDate(): \Datetime
    {
        return $this->publicationDate;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->author;
    }
}
