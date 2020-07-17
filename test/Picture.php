<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="picture")
* */
class Picture
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    public $id;

    /**
    * @ORM\Column(type="string")
    */
    public $title;

    /**
    * @ORM\Column(type="text")
    */
    public $content;

    /**
    * @ORM\Column(type="datetime", name="date")
    */
    public $date;
}