<?php
/**
 * Created by PhpStorm.
 * User: niklas
 * Date: 07.09.18
 * Time: 15:12
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="wishlist")
 */
class Wishlist
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="wishlist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="wishlist")
     */
    private $product;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $listname;

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
    public function getListname()
    {
        return $this->listname;
    }

    /**
     * @param mixed $listname
     */
    public function setListname($listname)
    {
        $this->listname = $listname;
    }

}