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
class FactoryMethod
{
    // The parameterized factory method
    public static function data($type,$obj)
    {
       // spl_autoload_register('FactoryMethod::autoload');
       
            $classname =  $type;
            
            return  new $classname($obj);
       
    }

    static function autoload($class_name){

        if (file_exists('data/' . strtolower($class_name) .'/'.strtolower($class_name).'.php')) {

        require 'data/' . strtolower($class_name) .'/'.strtolower($class_name).'.php';
       }

    }
   
}

?>
