<?php
// src/Service/StatusUpdater.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class StatusUpdater
{

	public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
    *Update the status of the entity with the content
    **/
    public function updateStatus(Request $request) {

      $content = json_decode($request->getContent(), true);
      $entity = 'App\Entity\\'.ucfirst($content['entity']);
   
    	$entity = $this->em->getRepository($entity)->findOneById($content['id']);
    	if( null !== $entity) {
    		$entity->setStatus($content['status']);
    		$this->em->persist($entity);
    		$this->em->flush();
    	}
    }

    /**
    *Update the highlight of the entity with the content
    **/
    public function updateHighlight(Request $request) {

      $content = json_decode($request->getContent(), true);
      $entity = 'App\Entity\\'.ucfirst($content['entity']);
   
    	$entity = $this->em->getRepository($entity)->findOneById($content['id']);
    	if( null !== $entity) {    
    		$entity->setHighlight($content['highlight']);
    		$this->em->persist($entity);
    		$this->em->flush();
    	}
    }
}