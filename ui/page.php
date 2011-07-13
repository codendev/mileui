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

class Page implements IObservable {

    private $observers = array();
    public $me;
    public $args;
    public $data=array();
    public $view;
    public $output=array();

    public function attach(IObserver $observer_in) {
        array_push($this->observers, $observer_in);
    }
    public function detach(IObserver $observer_in) {
        $key = array_search($this->observers, $observer_in);
    }
    public function notify() {
        foreach($this->observers as $obs) {
            $obs->update($this);
        }
    }
    public function create($args_in){

        $this->args=$args_in;
        $this->notify();
    }

    

    public function publish(){

        print $this->me;
    }

}

?>

