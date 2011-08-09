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

class Util {


    public static function read() {
        $files=array();
        if ($handle = opendir('../../base')) {
            echo "Directory handle: $handle\n";
            echo "Files:\n";

            /* This is the correct way to loop over the directory. */
            while (false !== ($file = readdir($handle))) {
                array_push($files, $file);
            }

            closedir($handle);
            return $files;
        }
    }
    public static function load($class) {

        return new $class();

    }

    static function curPageURL() {
       $pageURL=$_GET;
        
        return $pageURL;
    }

    public function textBox($name) {

        return "<input name='$name' ></input>";

    }
    public function textArea($name) {

        return "<textarea name='$name'></input>";

    }
    public function label($name) {

        return "<label>$name</label>";
    }
    public function dropDown($name,$options,$type) {

        $list="<select name='$name'>";
        foreach ($options as $key=>$item) {
            $list.="<option value='$key'>$item</option>";

        }
        $list.="</ul>";
        return $list;
    }

    public function table($name,$data) {

        $table="<table name='$name'>";

        $table="<th>";

        foreach($data[0] as $key=>$item) {
            $table.="<td>$key</td>";
        }
        $table.="</th>";
        foreach($data as $row) {
            foreach($row as $key=>$item) {
                $table.="<td>$item</td>";
            }
        }

        $table.="</table>";

    }
     static function redirect($action,$method){
        
    header("location:?obj=$action&mtd=$method");
    }







}


?>
