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

include_once('sys_default.php');

function __autoload($class_name) {
    if (file_exists('connector/mysql/class.' . strtolower($class_name) . '.php')) {
        require 'connector/mysql/class.' . strtolower($class_name) . '.php';
    }
    if (file_exists('../base/' . strtolower($class_name) . '.php')) {

        require '../base/' . strtolower($class_name) . '.php';

    }

    if (file_exists('../ui/' . strtolower($class_name) . '.php')) {

        require '../ui/' . strtolower($class_name) . '.php';
    }
    if (file_exists('../ui/interface/' . strtolower($class_name) . '.php')) {

        require '../ui/interface/' . strtolower($class_name) . '.php';
    }
    if (file_exists('../ui/lib/' . strtolower($class_name) . '.php')) {

        require '../ui/lib/' . strtolower($class_name) . '.php';
    }

    if (file_exists('action/' . strtolower($class_name) . '.php')) {

        require 'action/'.strtolower($class_name) . '.php';
    }
    if (file_exists('../data/' . strtolower($class_name) . '.php')) {

        require '../data/' . strtolower($class_name) . '.php';
    }
    if (file_exists('../data/' . strtolower($class_name) .'/'.strtolower($class_name).'.php')) {


        if ($handle = opendir('../data/' . strtolower($class_name) .'/')) {


            /* This is the correct way to loop over the directory. */
            while (false !== ($file = readdir($handle))) {
                if($file!="."&&$file!="..") {
                    require '../data/' . strtolower($class_name) .'/'.strtolower($file);

                }
            }


        }


        closedir($handle);

    }

    /*
     * System Defaults Initialize
     *
    */

    SysDefault::getDefault();

}
?>