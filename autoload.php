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
 * @author		codendev
 * @copyright           Copyright (c) 2011 - 2012, CodenDev.
 * @license		http://www.gnu.org/licenses/gpl.txt
 * @link		http://www.mileframework.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

// Class Autloader
function __autoload($class_name) {
    if (file_exists('base/' . strtolower($class_name) . '.php')) {

        require 'base/' . strtolower($class_name) . '.php';
    }
    if (file_exists('data/' . strtolower($class_name) . '.php')) {

        require 'data/' . strtolower($class_name) . '.php';
    }
    if (file_exists('data/' . strtolower($class_name) .'/'.strtolower($class_name).'.php')) {


        if ($handle = opendir('data/' . strtolower($class_name) .'/')) {

            while (false !== ($file = readdir($handle))) {
                if($file!="."&&$file!="..") {
                    require 'data/' . strtolower($class_name) .'/'.strtolower($file);
                   
                }
            }


        }


        closedir($handle);

    }
}
?>