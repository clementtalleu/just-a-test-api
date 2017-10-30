<?php


namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"product_read"}},
 *     "denormalization_context"={"groups"={"product_write"}}
 * })
 */
class Product
{
    /**
     * @var int.
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"product_read", "product_write"})
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Groups({"product_read", "product_write"})
     */
    private $name;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"product_read", "product_write"})
     */
    private $category;

    /**
     * @var int
     * @Assert\Type("integer")
     * @ORM\Column(type="integer", nullable=false)
     * @Groups({"product_read", "product_write"})
     */
    private $likes = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->likes;
    }

    /**
     * @param int $likes
     */
    public function setLikes(int $likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(?Category $category)
    {
        $this->category = $category;
    }
}
