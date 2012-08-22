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
class Action_User {

    private $page;

    public function  __construct($page) {

        $this->page=$page;
        $this->page->data["header"]=$this->page->block->create(array('action'=>'block','event'=>'header'));
        $this->page->data["footer"]=$this->page->block->create(array('action'=>'block','event'=>'footer'));
       // $this->page->data["sidebar"]=$this->page->block->create(array('action'=>'block','event'=>'sidebar'));



    }

    public function index() {

        $usr= new User();
        $offset=SysDefault::get('offset');
        $dbObj=FactoryMethod::data('mysql', $usr);
        $idx=empty($_GET["idx"])?1:$_GET["idx"];
        // $filter=array('username'=>$_POST["user"],'password'=>$_POST["password"]);
        $this->page->data["user"]=$dbObj->fetch(1)->limit(($idx-1)*$offset,$offset)->object_compile();
        $total=$dbObj->fetch(1)->count();
        $paging =new Pager($idx,$offset,$total);
        $paging->calculate();
        $this->page->data["paging"]= $paging;
        $this->page->data["idx"]= $idx;


    }
    public function add() {


    }
    public function update() {
        $usr= new User();
        $grp=new Group();

        $grpObj=FactoryMethod::data('mysql', $grp);
        $dbObj=FactoryMethod::data('mysql', $usr);
        $id=Util::get('id');
        $filter=array('user.id'=>$id);
        $user=$dbObj->filter($filter)->fetch(3)->object_compile();

        if(!empty($_POST)) {

            $usr_grp= new User_Group();
            $usgrObj=FactoryMethod::data('mysql', $usr_grp);
            foreach($user[0]->user_group as $item){
                $usr_grp->id=$item->id;
                $usgrObj->delete();

            }
            $user=array();
           
            $usr->id=$id;
            $usr->username=Util::post('name');
            $groups=Util::post('groups');
        
            foreach($groups as $item) {
                $user_group=new User_Group();
                $usref=new User();
                $usref->id=$id;
                $user_group->user=$usref;
                $grp=new Group();
                $grp->id=$item;
                $user_group->group=$grp;
                $user_groups[]=$user_group;
            }
            $usr->user_group=$user_groups;
            $user[]=$usr;

            $dbObj->save();

        }
       
        $this->page->data['groups']=$grpObj->fetch(1)->object_compile();
        $this->page->data["user"]=$user;
    }
  
}

?>
