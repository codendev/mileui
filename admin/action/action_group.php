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
class Action_Group {

    private $page;

    public function  __construct($page) {

        $this->page=$page;
        $this->page->data["header"]=$this->page->block->create(array('action'=>'block','event'=>'header'));
        $this->page->data["footer"]=$this->page->block->create(array('action'=>'block','event'=>'footer'));
     

    }

    public function index() {

        $grp= new Group();
        $offset=SysDefault::get('offset');
        $dbObj=FactoryMethod::data('mysql', $grp);
        $idx=empty($_GET["idx"])?1:$_GET["idx"];
       // $filter=array('username'=>$_POST["user"],'password'=>$_POST["password"]);
        $this->page->data["group"]=$dbObj->fetch(1)->limit(($idx-1)*$offset,$offset)->object_compile();
        $total=$dbObj->fetch(1)->count();
        $paging =new Pager($idx,$offset,$total);
        $paging->calculate();
        $this->page->data["paging"]= $paging;
        $this->page->data["idx"]= $idx;


    }
    public function add() {


    }
    public function update() {


    }
    public function grid() {


    }

}

?>
