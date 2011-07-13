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
    class Config
    {
        // Singleton object. Leave $me alone.
        private static $me;


        // ...For Auth Class
        public $authDomain;         // Domain to set for the cookie
        public $authSalt;           // Can be any random string of characters

        // ...For Database Class
        public $dbReadHost;   // Database read-only server
        public $dbWriteHost;  // Database read/write server
        public $dbName;
        public $dbReadUsername;
        public $dbWriteUsername;
        public $dbReadPassword;
        public $dbWritePassword;

        public $dbOnError; // What do do on a database error (see class.database.php for details)
        public $dbEmailOnError; // Email an error report on error?

        // Add your config options here...
        public $useDBSessions; // Set to true to store sessions in the database



        // Singleton constructor
        private function __construct()
        {
           
            $this->local();

        }

        // Get Singleton object
        public static function getConfig()
        {
            if(is_null(self::$me))
                self::$me = new Config();
            return self::$me;
        }

        // Allow access to config settings statically.
        // Ex: Config::get('some_value')
        public static function get($key)
        {
            return self::$me->$key;
        }


        // Add code/variables to be run only on production servers
        private function production()
        {
            ini_set('display_errors', '0');

            $this->dbReadHost      = 'localhost';
            $this->dbWriteHost     = 'localhost';
            $this->dbName          = '';
            $this->dbReadUsername  = '';
            $this->dbWriteUsername = '';
            $this->dbReadPassword  = '';
            $this->dbWritePassword = '';
            $this->dbOnError       = '';
            $this->dbEmailOnError  = false;
        }

        // Add code/variables to be run only on staging servers
        private function staging()
        {
            ini_set('display_errors', '1');
            ini_set('error_reporting', E_ALL);

            define('WEB_ROOT', '');

            $this->dbReadHost      = 'localhost';
            $this->dbWriteHost     = 'localhost';
            $this->dbName          = '';
            $this->dbReadUsername  = '';
            $this->dbWriteUsername = '';
            $this->dbReadPassword  = '';
            $this->dbWritePassword = '';
            $this->dbOnError       = 'die';
            $this->dbEmailOnError  = false;
        }

        // Add code/variables to be run only on local (testing) servers
        private function local()
        {
            ini_set('display_errors', '1');
            ini_set('error_reporting', E_ALL);

                     $this->dbReadHost      = 'localhost';
            $this->dbWriteHost     = 'localhost';
            $this->dbName          = 'mile';
            $this->dbReadUsername  = 'root';
            $this->dbWriteUsername = 'root';
            $this->dbReadPassword  = '';
            $this->dbWritePassword = '';
            $this->dbOnError       = 'die';
            $this->dbEmailOnError  = false;
        }

        // Add code/variables to be run only on when script is launched from the shell
        private function shell()
        {
            ini_set('display_errors', '1');
            ini_set('error_reporting', E_ALL);


            $this->dbReadHost      = 'localhost';
            $this->dbWriteHost     = 'localhost';
            $this->dbName          = 'resultonline';
            $this->dbReadUsername  = 'root';
            $this->dbWriteUsername = 'root';
            $this->dbReadPassword  = '';
            $this->dbWritePassword = '';
            $this->dbOnError       = false;
            $this->dbEmailOnError  = true;
        }


    }
