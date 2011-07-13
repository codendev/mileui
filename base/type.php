<?php
/**
 * MileFramwork
 *
 * Web Framework for PHP 5.2 or newer
 *
 * Licensed under GPL
 * Redistributions of files must retain the above copyright notice.
 *
 * @package		MileFramework
 * @author		Shahrukh Hussain
 * @copyright           Copyright (c) 2011 - 2012, Abydeen Business Consulting.
 * @license		http://www.gnu.org/licenses/gpl.txt
 * @link		http://www.mileframework.com
 * @since		Version 1.0
 * @filesource
 */
class Type extends Field {
  
    var $size;
    var $type;
   
   function  Type($name,$description,$required,$size,$type) {
   
             parent::Field($this, $name, $description, $required );
             $this->size=$size;
             $this->type=$type;
   }

   
}
?>
