<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 07.09.18
 * Time: 15:13
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="coupon")
 */
class Coupon
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="coupon")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

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
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param mixed $cart
     */
    public function setCart($cart)
    {
        $this->cart = $cart;
    }

    /**
     * @return mixed
     */
    public function getCouponCode()
    {
        return $this->coupon_code;
    }

    /**
     * @param mixed $coupon_code
     */
    public function setCouponCode($coupon_code)
    {
        $this->coupon_code = $coupon_code;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }
    /**
     * @ORM\OneToOne(targetEntity="Cart", inversedBy="coupon")
     * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
     */
    private $cart;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $coupon_code;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $discount;
}