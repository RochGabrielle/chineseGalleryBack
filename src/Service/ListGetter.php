<?php
// src/Service/FileUploader.php
namespace App\Service;


class ListGetter
{
	public function getList( $arrayOfObjects, array $arrayOfLanguages, string $fullList = "no", string $entityName = '' ) {

	  $list = array();
            if( !empty($arrayOfObjects)) {
                $i =0;
                foreach ($arrayOfObjects as $element){
                 $secondaryList = array();
                 // place holder in entity table
                 $secondaryList["placeholder"] = (method_exists($element,'getPlaceholder'))?$element->getPlaceholder():$element->getName();
                 $secondaryList["id"] = $element->getId();
                 // if translation are needed $fullList is set to true
                    foreach ($arrayOfLanguages as $lang) {
                        $secondaryList["name_".$lang] = (method_exists($element->translate($lang),'getName'))?$element->translate($lang)->getName():$element->translate($lang)->getDescription() ;
                        }
                
                // if it s a theme, the media is returned aswell
                if($entityName == 'theme' ) {
                    if( null !== $element->getMedia()) {
                    $secondaryList["media"] = $element->getMedia()->getPlaceholder();
                    $secondaryList["mediaId"] = $element->getMedia()->getId();
                }

                }
                if($entityName == 'discount' ) {
                    $secondaryList["discountrate"] = $element->getDiscountrate();
                    $secondaryList["discountrate"] = $element->getDiscountrate();
                }


                $list[$i] = $secondaryList;
                $i++;
            }
        }
        return $list;
}

}