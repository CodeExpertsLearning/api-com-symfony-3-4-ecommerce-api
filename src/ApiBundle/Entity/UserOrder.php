<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as GEDMO;

/**
 * UserOrder
 *
 * @ORM\Table(name="user_order")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\UserOrderRepository")
 */
class UserOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="items", type="text")
     */
    private $items;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable()
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     */
    private $user;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="pag_seguro_code", type="text", nullable=true)
	 */
    private $pagSeguroCode;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="pag_seguro_status", type="integer", nullable=true)
	 */
    private $pagSeguroStatus;

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
     * Set items
     *
     * @param string $items
     *
     * @return UserOrder
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get items
     *
     * @return string
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return UserOrder
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
     * @return UserOrder
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
     * Set userId
     *
     * @param integer $userId
     *
     * @return UserOrder
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

	/**
	 * @return string
	 */
	public function getPagSeguroCode() {
		return $this->pagSeguroCode;
	}

	/**
	 * @param string $pagSeguroCode
	 *
	 * @return UserOrder
	 */
	public function setPagSeguroCode($pagSeguroCode)
	{
		$this->pagSeguroCode = $pagSeguroCode;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPagSeguroStatus()
	{
		return $this->pagSeguroStatus;
	}

	/**
	 * @param string $pagSeguroStatus
	 *
	 * @return UserOrder
	 */
	public function setPagSeguroStatus($pagSeguroStatus)
	{
		$this->pagSeguroStatus = $pagSeguroStatus;

		return $this;
	}


}

