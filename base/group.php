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
class Group extends Base {
    //put your code here
    public static $me;
    public $name;
    public $user_group;
    public $object_group;
  

    public function  __construct() {

        Group::$me['name']= new Type('name','',true,255,'text');
        Group::$me['user_group']= new Reference('user_group','','user_group','group','onetomany');
        Group::$me['object_group']= new Reference('object_group','','object_group','group','onetomany');
        
        parent::__construct($this);

    }





}
?>
