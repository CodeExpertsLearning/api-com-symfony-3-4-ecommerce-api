<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as GEDMO;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;


/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Groups({"user_index"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank(message="Este campo é obrigatório")
     * @JMS\Groups({"user_index"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank(message="Este campo é obrigatório")
     * @JMS\Groups({"user_index"})
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message="Este campo é obrigatório")
     * @Assert\Email(message="Este campo é obrigatório")
     * @JMS\Groups({"user_index"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\NotBlank(message="Este campo é obrigatório")
     */
    private $password;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="roles", type="string", length=255)
	 * @Assert\NotBlank(message="Este campo é obrigatório")
	 * @JMS\Groups({"user_index"})
	 */
	private $roles;

	/**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
	 * @JMS\Groups({"user_index"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Gedmo\Timestampable()
     * @JMS\Groups({"user_index"})
     */
    private $updatedAt;

	/**
	 * @ORM\OneToMany(targetEntity="UserOrder", mappedBy="user_id")
	 */
    private $orders;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

	/**
     * User Interface - Security Component
     */

	public function getRoles()
	{
		return !$this->roles ? [] : explode(',', $this->roles);
	}

	public function setRoles($role)
	{
		$this->roles = $role;
	}

	public function getSalt()
	{
		return null;
	}

	public function getUsername()
	{
		return $this->email;
	}

	public function eraseCredentials()
	{
		return null;
	}
}

