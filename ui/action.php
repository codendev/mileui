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

class Action implements IObserver {

    public function update(IObservable $subject_in) {

        $action=$this->dispatch($subject_in);
     
    }
    private function dispatch($page) {
       
        extract($page->args);
        var_dump($page->args);
        $ob='Action_'.ucfirst($action);
        $ins=new $ob($page);
        return $ins->$event();


    }



}
?>
