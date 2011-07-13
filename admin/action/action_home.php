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
class Action_Home{

    public function index(){
        $data['test']="Hello World";
        return 
        array('data'=>$data,
              'output'=>array('xml','view'),
              'template'=>'view/user/test.php'
            );


    }
    public function add(){


    }
    public function update(){


    }
    public function grid(){


    }

}

?>
