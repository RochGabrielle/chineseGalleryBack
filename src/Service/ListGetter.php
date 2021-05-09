<?php
// src/Service/FileUploader.php
namespace App\Service;

use  App\Entity\Article;


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
                
                // if it s a theme, the category is returned aswell
       /*         if($entityName == 'theme' ) {
                    if( null !== $element->getCategory()) {
                    $secondaryList["category"] = $element->getCategory()->getPlaceholder();
                    $secondaryList["categoryId"] = $element->getCategory()->getId();
                }

                } */
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

    /**
    * returns an array that contains all infos necessary to display an article
    * @var $artical Article
    * @var $lang language in format fr_fr, en_gb, cn_cn
    * @return array
    **/
    public function getArticleInfo(Article $article, string $lang)
    {
      $simpleElementToReturn = array("product", "material","discount", "form", "theme");
      $simpleArtistDynastyToReturn = array("artist", "dynasty");
      $articleInfo = array();

       $articleInfo["id"]          = $article->getId()   !== null ? $article->getId() : "";
       $articleInfo["title"]       = $article->getTitle()!== null ? $article->getTitle() : "";
       $articleInfo["birth"]       = $article->getBirth()!== null ? $article->getBirth() : "";
       $articleInfo["price"]       = $article->getPrice()!== null ? $article->getPrice() : "";
       $articleInfo["status"]      = $article->getStatus()!== null ? $article->getStatus() : "";
       $articleInfo["bigImage"]  = $article->getSmall()!== null ? $article->getBig() : "";
       $articleInfo["size"]        = $article->getSize()!== null ? $article->getSize() : "";
       $articleInfo["description"] = $article->translate($lang)->getDescription()!== null ? $article->translate($lang)->getDescription() : "";
       $articleInfo["title_cn"]    = $article->translate("cn_cn")->getTitle()!== null ? $article->translate("cn_cn")->getTitle() : "";

          /* $themes = $element->getTheme();
           $theme = '';
           foreach ($themes as $t) {
             $theme = $theme . ' '.$t->getPlaceholder();
             $article["category"] = null == $t->getCategory()? '': $t->getCategory()->getPlaceholder();
           }
           $article["theme"] = $theme;
           */
        foreach($simpleElementToReturn as $item) 
        {
            $getter = "get".ucfirst($item);
            if( null !== $article->$getter()) 
            {
            $articleInfo[$item] = $article->$getter()->translate($lang)->getName() !== null ? $article->$getter()->translate($lang)->getName() : "";
            }     
        }
          
        foreach($simpleArtistDynastyToReturn as $item) 
        {
          $getter = "get".ucfirst($item);
          if( null !== $article->$getter()) 
          {
            $articleInfo[$item][] = array("id" => $article->$getter()->getId(), "name" => $article->$getter()->translate($lang)->getName() !== null ? $article->$getter()->translate($lang)->getName() : "");
          }
        }

/*
          $sizes = $element->getSizes();
          foreach($sizes as $s) {
            $article["sizes"][] = array("id" => $s->getId(), 
              "width" => $s->getWidth(),
              "length" => $s->getLength(),
              "sizecategory" => array("id" => null == $s->getSizecategory()? 0: $s->getSizecategory()->getId(),
                                      "placeholder" =>  null == $s->getSizecategory()? '': $s->getSizecategory()->getPlaceholder()
                                    )
            );
          }
*/
          return $articleInfo;
    }

    /**
    * returns an array that contains all summarized infos necessary to display an article in gallery
    * @var $artical Article
    * @var $lang language in format fr_fr, en_gb, cn_cn
    * @return array
    **/
    public function getArticleInfoForGallery(Article $article, string $lang)
    {
      $simpleElementToReturn = array("material", "form", "theme");
      $simpleArtistDynastyToReturn = array("artist", "dynasty");
      $articleInfo = array();

       $articleInfo["id"]          = $article->getId();
       $articleInfo["title"]       = $article->getTitle();
       $articleInfo["birth"]       = $article->getBirth();      
       $articleInfo["status"]      = $article->getStatus();
       $articleInfo["smallimage"]  = $article->getSmall();
       $articleInfo["theme"]       = $article->getSize();
       $articleInfo["title_cn"]    = $article->translate("cn_cn")->getTitle();

          /* $themes = $element->getTheme();
           $theme = '';
           foreach ($themes as $t) {
             $theme = $theme . ' '.$t->getPlaceholder();
             $article["category"] = null == $t->getCategory()? '': $t->getCategory()->getPlaceholder();
           }
           $article["theme"] = $theme;
           */
        foreach($simpleElementToReturn as $item) 
        {
            $getter = "get".ucfirst($item);
            if( null !== $article->$getter()) 
            {
            $articleInfo[$item] = $article->$getter()->translate($lang)->getName();
            }     
        }
          
        foreach($simpleArtistDynastyToReturn as $item) 
        {
          $getter = "get".ucfirst($item);
          if( null !== $article->$getter()) 
          {
            $articleInfo[$item][] = array("id" => $article->$getter()->getId(), "name" => $article->$getter()->translate($lang)->getName());
          }
        }

/*
          $sizes = $element->getSizes();
          foreach($sizes as $s) {
            $article["sizes"][] = array("id" => $s->getId(), 
              "width" => $s->getWidth(),
              "length" => $s->getLength(),
              "sizecategory" => array("id" => null == $s->getSizecategory()? 0: $s->getSizecategory()->getId(),
                                      "placeholder" =>  null == $s->getSizecategory()? '': $s->getSizecategory()->getPlaceholder()
                                    )
            );
          }
*/
          return $articleInfo;
    }

    /**
    * returns an array that contains all infos necessary to edit an article
    * @var $artical Article
    * @return array
    **/
    public function getAllArticleInfoForEdit(Article $article)
    {
      $articleInfo = array();
           $articleInfo["id"] = $article->getId();
           $articleInfo["title"] = $article->getTitle();
           $articleInfo["birth"] = $article->getBirth();
           $articleInfo["price"] = $article->getPrice();
           $articleInfo["status"] = $article->getStatus();
           $articleInfo["smallimage"] = $article->getSmall();
           $articleInfo["bigimage"] = $article->getBig();
           $articleInfo["highlight"] = $article->getHighlight()!== null ? $article->getHighlight() : "";
           $articleInfo["size"] = $article->getSize() !== null ?$article->getSize() : "" ;
           $traductionList = array("description", "title");
           foreach ($traductionList as $tl) {
            $getter = "get".ucfirst($tl);
             foreach ($this->getParameter('languages') as $lang) {
            $articleInfo[$tl."_".$lang] = $article->translate($lang)->$getter();
          }
           }
           
          $simpleElementToReturn = array("product", "material","discount", "museum", "form", "theme");

          foreach($simpleElementToReturn as $item) {
            $getter = "get".ucfirst($item);
            if( null !== $article->$getter()) {
            $articleInfo[$item][] = array("id" => $article->$getter()->getId(), "placeholder" => $article->$getter()->translate($lang)->getPlaceholder() !== null ? $article->$getter()->translate($lang)->getPlaceholder() : "");
          } else {
            $articleInfo[$item][] = array("id" => 0, "placeholder" => "field undefined");
          }
          }
          $simpleArtistDynastyToReturn = array("artist", "dynasty");
          foreach($simpleArtistDynastyToReturn as $item) {
            $getter = "get".ucfirst($item);
            if( null !== $article->$getter()) {
            $articleInfo[$item][] = array("id" => $article->$getter()->getId(), "name" => $article->$getter()->translate($lang)->getName() !== null ? $article->$getter()->translate($lang)->getName() : "");
          } else {
            $articleInfo[$item][] = array("id" => 0, "placeholder" => "field undefined");
          }
          }

/*
          $sizes = $article->getSizes();
          foreach($sizes as $s) {
            $articleInfo["sizes"][] = array("id" => $s->getId(), 
              "width" => $s->getWidth(),
              "length" => $s->getLength(),
              "sizecategoryId" => null == $s->getSizecategory() ?  0: $s->getSizecategory()->getId(),
              "sizecategory" =>  null == $s->getSizecategory() ? "undefined":$s->getSizecategory()->getPlaceholder()
            );
          }
*/
/* 
          $articleInfo["category"] = array("id" => 0,
                          "placeholder" => '');
            $articleInfo["theme"][] = array( "id" => 0,
                           "placehoder" => '',
                           "category" => '',
                           "categoryId" => 0);
          if(null !== $element->getTheme()  && !empty($element->getTheme())) {

            foreach($element->getTheme() as $t) {
$articleInfo["theme"][] = array( "id" => null == $t->getId()? '0': $t->getId(),
                           "placehoder" => null == $t->getPlaceholder()? '': $t->getPlaceholder(),
                           "category" => null == $t->getCategory()? '':$t->getCategory()->getPlaceholder(),
                           "categoryId" => null == $t->getCategory()? 0:$t->getCategory()->getId());
$articleInfo["category"] = array("id" => null == $t->getCategory()? '':$t->getCategory()->getId(),
                          "placeholder" => null == $t->getCategory()? 0:$t->getCategory()->getPlaceholder());
            }
          } 
          if(null == $element->getTheme() || (null !== $element->getTheme()  && empty($element->getTheme()))) {
            dd('in the condition');
                       
          }
*/
          return $articleInfo;
    }

}