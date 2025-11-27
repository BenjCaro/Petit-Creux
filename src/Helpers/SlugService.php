<?php

namespace Carbe\Petitcreuxv2\Helpers;

class SlugService {


  /**
  * A partir du titre donné par l'utilisateur 
  * cette methode permet de créer le slug 
  *
  */

     public static function generateSlug(string $string) :string {

          $string = mb_strtolower($string);
          $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
          $string = preg_replace('/[^\w\s-]/', '', $string);
          $string = preg_replace('/[\s]+/', '-', $string);  
          $slug = trim($string, '-');
          return $slug;
   }
}