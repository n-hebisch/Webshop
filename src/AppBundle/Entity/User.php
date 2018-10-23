<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 07.09.18
 * Time: 13:48
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $username;
    /**

     * @ORM\OneToMany(targetEntity="Address", mappedBy="user")
     */
    private $address;
    /**
     * @ORM\OneToOne(targetEntity="Wishlist", mappedBy="user")
     */
    private $wishlist;
    /**
     * @ORM\OneToOne(targetEntity="Cart", mappedBy="user")
     */
    private $cart;
    /**
     * @ORM\OneToMany(targetEntity="Coupon", mappedBy="user")
     */
    private $coupon;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getAddresses()
    {
        return $this->address;
    }

    /**
     * @param mixed $addresses
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }

    /**
     * @return mixed
     */
    public function getWishlist()
    {
        return $this->wishlist;
    }

    /**
     * @param mixed $wishlist
     */
    public function setWishlist($wishlist)
    {
        $this->wishlist = $wishlist;
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
    public function getCoupons()
    {
        return $this->coupon;
    }

    /**
     * @param mixed $coupons
     */
    public function setCoupons($coupons)
    {
        $this->coupons = $coupons;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


}