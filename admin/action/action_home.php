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
class Action_Home {

    private $page;

    public function  __construct($page) {

        $this->page=$page;
       
    }
      
      
    public function index() {
         
        $this->page->data['header']=$this->page->block->create(array('obj'=>'home','mtd'=>'header'));
        $this->page->data['footer']=$this->page->block->create(array('obj'=>'home','mtd'=>'footer'));
        
       

    }
    public function login() {

        $this->page->data['header']=$this->page->block->create(array('obj'=>'home','mtd'=>'header'));
        $this->page->data['footer']=$this->page->block->create(array('obj'=>'home','mtd'=>'footer'));
        if(!empty($_POST)) {
            $usr= new User();
            $usr->id=1;
            $usr->username=$_POST["user"];
            $this->page->set_state('user',$usr);
            Util::redirect('home', 'index');
        }
        $this->page->view='view/home/login.php';
        

    }
    public function logout() {
        $this->page->unset_state('user');
        Util::redirect('home', 'index');
    }
    public function header() {


    }
    public function footer(){

        
    }

}

?>
