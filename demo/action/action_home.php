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
class Action_Home {

    private $page;

    public function  __construct($page) {

        $this->page=$page;
        $this->page->data["header"]=$this->page->block->create(array('action'=>'block','event'=>'header'));
        $this->page->data["footer"]=$this->page->block->create(array('action'=>'block','event'=>'footer'));
        $this->page->data["sidebar"]=$this->page->block->create(array('action'=>'block','event'=>'sidebar'));
       


    }


    public function index() {
     
    


    }
    public function login() {

      
        if(!empty($_POST)) {
            $usr= new User();
            $dbObj=FactoryMethod::data('mysql', $usr);

            $rs=$dbObj->filter(array('username'=>$_POST["user"],'password'=>$_POST["password"]))->fetch(2)->object_compile();

            if(!empty($rs)) {
                $usr->id=1;
                $usr->username=$_POST["user"];
                $this->page->set_state('user',$usr);
                Util::redirect('home', 'index');
            }
        }
        $this->page->view='view/home/login.php';


    }
    public function logout() {
        $this->page->unset_state('user');
        Util::redirect('home', 'index');
    }


}

?>
