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
 * @link		http://www.codendev.com
 * @since		Version 1.0
 * @filesource
 */
    class SysDefault
    {
        // Singleton object. Leave $me alone.
        private static $me;

        public $action;
        public $event;
        public $offset;

        // Singleton constructor
        private function __construct()
        {

            $this->local();

        }

        // Get Singleton object
        public static function getDefault()
        {
            if(is_null(self::$me))
                self::$me = new SysDefault();
            return self::$me;
        }

        // Allow access to config settings statically.
        // Ex: Config::get('some_value')
        public static function get($key)
        {
            return self::$me->$key;
        }

        // Add code/variables to be run only on staging servers
        private function local()
        {
            ini_set('display_errors', '1');
            ini_set('error_reporting', E_ALL);

            $this->action      = 'home';
            $this->event     = 'index';
            $this->offset     = '2';
          
         
        }

    


    }
