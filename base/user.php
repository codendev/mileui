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
class User extends Base {
    //put your code here
    static $me;
    public $username;
    public $password;
    public $group;

    public function  __construct() {

        User::$me['username']= new Type('username','',true,255,'text');
        User::$me['password']= new Type('password','',true,255,'sha');
        User::$me['group']= new Reference('group','','group','id','onetoone');

        parent::__construct($this);

    }


    
   


}
?>
