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

class Action implements IObserver {

    public function update(IObservable $subject_in) {

        $action=$this->dispatch($subject_in);
        $subject_in->data['action']=$action['data'] ;
        $subject_in->view=$action['template'] ;
        $subject_in->output=$action['output'] ;


       

    }
    private function dispatch($page) {

        extract($page->args);
        $ob='Action_'.ucfirst($obj);
        $ins=new $ob();
        return $ins->$mtd();


    }



}
?>
