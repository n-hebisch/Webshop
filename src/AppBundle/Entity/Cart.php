<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 07.09.18
 * Time: 15:07
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cart")
 */
class Cart
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="cart")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\ManyToMany(targetEntity="Product")
     * @ORM\JoinTable(name="cart_product",
     *      joinColumns={@ORM\JoinColumn(name="cart_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    private $product;
    /**
     * @ORM\OneToOne(targetEntity="Coupon", mappedBy="cart")
     */
    private $coupon;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * @param mixed $coupon
     */
    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getNumberOfArticles()
    {
        return $this->numberOfArticles;
    }

    /**
     * @param mixed $numberOfArticles
     */
    public function setNumberOfArticles($numberOfArticles)
    {
        $this->numberOfArticles = $numberOfArticles;
    }
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $amount;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $numberOfArticles;

}