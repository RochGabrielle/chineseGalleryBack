<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="size")
* */
class Size
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    public $id;

    /**
    * @ORM\Column(type="float")
    */
    public $length;

    /**
    * @ORM\Column(type="float")
    */
    public $width;

}