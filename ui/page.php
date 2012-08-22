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

class Page implements IObservable {

    private $observers = array();
    public $self;
    public $args;
    public $data=array();
    public $view;
    public $output=array();
    public $state;
    public $block;


    public function  __construct() {
        $this->args=Util::curPageURL();
    }

    public function attach(IObserver $observer_in) {
        array_push($this->observers, $observer_in);
    }
    public function detach(IObserver $observer_in) {
        $key = array_search($this->observers, $observer_in);
    }
    public function notify() {
        $args=func_get_args();
        if(!empty($args)) {
            foreach($this->observers as $obs) {
                if(get_class($this)==$args[0])
                    $obs->update($this);
            }
        }
        else {

            foreach($this->observers as $obs) {
                $obs->update($this);
            }

        }

    }
    public function create() {
        $this->notify();
    }

    public function set_state($name,$value) { 
        $this->state[$name]=$value;
        $this->notify('Session');
    }
    public function get_state($name) {
        return $_SESSION[$name];
       
    }
    public function unset_state($name) {
       $this->state[$name]=null;
       $this->notify('Session');

    }
    
    public function publish() {

        print $this->self;
    }

}

?>

