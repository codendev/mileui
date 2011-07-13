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

function __autoload($class_name) {
    if (file_exists('connector/mysql/class.' . strtolower($class_name) . '.php')) {
        require 'connector/mysql/class.' . strtolower($class_name) . '.php';
    }
    if (file_exists('base/' . strtolower($class_name) . '.php')) {

        require 'base/' . strtolower($class_name) . '.php';
    }

    if (file_exists('../ui/' . strtolower($class_name) . '.php')) {

        require '../ui/' . strtolower($class_name) . '.php';
    }
    if (file_exists('../ui/interface/' . strtolower($class_name) . '.php')) {

        require '../ui/interface/' . strtolower($class_name) . '.php';
    }

    if (file_exists('action/' . strtolower($class_name) . '.php')) {

        require 'action/'.strtolower($class_name) . '.php';
    }


}
?>