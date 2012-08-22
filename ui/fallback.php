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

class FallBack implements IObserver {


    public function update(IObservable $subject_in) {

        $this->fallbacks($subject_in);

    }

  
    public function fallbacks($page) {

        
         if(empty($page->args)){
             $page->args["action"]=SysDefault::get('action');
             $page->args["event"]=SysDefault::get('event');
           
         }
       
    }




}
?>
