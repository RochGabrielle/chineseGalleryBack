<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserController extends Controller
{
 protected $languages = array("fr_fr", "en_gb");

 private $passwordEncoder;

      public function __construct(UserPasswordEncoderInterface $passwordEncoder)
      {
           $this->passwordEncoder = $passwordEncoder;
       }

   


  }