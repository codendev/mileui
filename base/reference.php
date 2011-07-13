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
class Reference extends Field {
    //put your code here
  
    public $relation;
    public $map;
    public $relationField;
    public $cascade;
   
  
   function  Reference($name,$description,$relation,$relationField,$map,$cascade=False) {
      

      $this->relation=$relation;
      $this->relationField=$relationField;
      $this->map=$map;
      $this->cascade=$cascade;


      parent::Field($this, $name, $description,true);

    }

  
}
?>
