<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"user_read"}},
 *     "denormalization_context"={"groups"={"user_write"}},
 *     "filters"={"user.search"}
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"user_read" })
     */
    protected $id;

    /**
     * @Groups({"user_read", "user_write"})
     */
    protected $email;

    /**
     * @Groups({"user_read", "user_write" })
     */
    protected $username;

    /**
     * @Groups({"user_read"})
     */
    protected $plainPassword;

    /**
     * @Groups({"user_read", "user_write" })
     */
    protected $roles;

    /**
     * @var string
     * @Groups({"user_read", "user_write" })
     * @ORM\Column(type="string", nullable=true)
     */
    protected $newsletterToken;

    public function __construct()
    {
        parent::__construct();
        $this->newsletterToken = random_int(1, 999999999);
    }

    /**
     * @return string
     */
    public function getNewsletterToken(): string
    {
        return $this->newsletterToken;
    }

    /**
     * @param string $newsletterToken
     */
    public function setNewsletterToken(string $newsletterToken)
    {
        $this->newsletterToken = $newsletterToken;
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }
}