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

class View implements IObserver{


    public function update(IObservable $subject_in) {

               
        if($subject_in->view==NULL)
        {
            $subject_in->view=$this->loadView($subject_in);
           
        } 
         $this->compile($subject_in);

     }

     private function loadView($subject_in){
      
         extract($subject_in->args);
         return  'view/'.strtolower($action).'/'.strtolower($event).'.php';

     }
     

     public function compile($subject_in){

       extract($subject_in->data);
       ob_start();

       require($subject_in->view);

       $applied_template = ob_get_contents();
       ob_end_clean();

       $subject_in->self=$applied_template;

    }

}
?>
