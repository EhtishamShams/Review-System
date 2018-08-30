<?php
/**
 * Created by PhpStorm.
 * User: ehtisham
 * Date: 16/08/2018
 * Time: 5:20 AM
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Serializer\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Serializer\Expose()
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @Serializer\Expose()
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @Serializer\Expose()
     * @ORM\Column(type="decimal")
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $picPath;

    /**
     * @ORM\ManyToOne(targetEntity="Shop", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @ORM\OneToMany(targetEntity="Review", mappedBy="product")
     */
    private $reviews;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getPicPath()
    {
        return $this->picPath;
    }

    /**
     * @param mixed $picPath
     */
    public function setPicPath($picPath)
    {
        $this->picPath = $picPath;
    }

    /**
     * @return mixed
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * @param mixed $shop
     */
    public function setShop($shop)
    {
        $this->shop = $shop;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }



    public function __toString()
    {
        return $this->name;
    }

}