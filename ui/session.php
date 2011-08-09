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

class Session implements IObserver {


    public function update(IObservable $subject_in) {

        $this->createSession();
        $this->setSession($subject_in);

    }

    private function createSession() {
        @session_start();


    }

    public function setSession($obj) {

      
        if(!empty($obj->state)){
             $_SESSION[key($obj->state)]=$obj->state[key($obj->state)];
           
        }
      
        foreach($_SESSION as $key=>$item){ 
            if($item==NULL)
                session_unregister($key);
        }
     
       
    }




}
?>
