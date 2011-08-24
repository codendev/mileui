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
class Action_Object {

    private $page;

    public function  __construct($page) {

        $this->page=$page;
        $this->page->data["header"]=$this->page->block->create(array('action'=>'block','event'=>'header'));
        $this->page->data["footer"]=$this->page->block->create(array('action'=>'block','event'=>'footer'));
     

    }

    public function index() {

        $grp= new Object();
        $offset=SysDefault::get('offset');
        $dbObj=FactoryMethod::data('mysql', $grp);
        $idx=empty($_GET["idx"])?1:$_GET["idx"];
        $this->page->data["object"]=$dbObj->fetch(1)->limit(($idx-1)*$offset,$offset)->object_compile();
                
        $paging =new Pager($idx,$offset,$dbObj->object_count());
        $paging->calculate();
        $this->page->data["paging"]= $paging;
        $this->page->data["idx"]= $idx;


    }
    public function add() {


    }
    public function update() {
        $obj= new Object();
        $grp=new Group();

        $grpObj=FactoryMethod::data('mysql', $grp);
        $dbObj=FactoryMethod::data('mysql', $obj);
        $id=Util::get('id');
        $filter=array('object.id'=>$id);
        $object=$dbObj->filter($filter)->fetch(4)->object_compile();

      
        if(!empty($_POST)) {

            $obj_grp= new Object_Group();
            $usgrObj=FactoryMethod::data('mysql', $obj_grp);
            foreach($object[0]->object_group as $item){
                $obj_grp->id=$item->id;
                $usgrObj->delete();

            }
            $object=array();

            $obj->id=$id;
            $obj->name=Util::post('name');
            $user_groups=Util::post('user_group');
            

            foreach($user_groups['groups'] as $key=>$item) {
                $object_group=new Object_Group();
                $objref=new Object();
                $objref->id=$id;
                $object_group->object=$objref;
                $grp=new Group();
                $grp->id=$item;
                $object_group->group=$grp;
                $object_groups[]=$object_group;
                $object_group->read=isset($user_groups['reads'][$key])?$user_groups['reads'][$key]:0;
                $object_group->write=isset($user_groups['writes'][$key])?$user_groups['writes'][$key]:0;
            }
            $obj->object_group=$object_groups;
            $object[]=$obj;
          
            $dbObj->save();

        }

        $this->page->data['groups']=$grpObj->fetch(1)->object_compile();
      
        $this->page->data["object"]=$object;
      
    }
    public function grid() {


    }

}

?>
