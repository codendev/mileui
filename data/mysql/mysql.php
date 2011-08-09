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

class Mysql extends Factory {
   

    private $base;
    private $idColumnName="id";
    private $condition=array();
    private $join=array();
    private $obj=array();
    private $load=array();
    private $depth=0;
    private $level=0;



    public function  __construct($baseIn) {
        $this->base=$baseIn;
        Config::getConfig();
    }

    public function initialize() {
        $this->createEntity($this);
    }

    public function createEntity() {

        $db= Database::getDatabase();
        $table=strtolower(get_class($this->base));
        $rs=$db->query("show tables like '".$table."'");
        $attributes=get_object_vars($this->base);

        //var_dump($properties);


        if($db->hasRows($rs)==false) {
            $rs=$db->query("CREATE TABLE `".$table."`"."(id int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (id))");

        }


        foreach ($attributes as $key=>$item) {

            $fields = $db->query("SHOW COLUMNS FROM `$table`");
            $rs =  $db->getRows($fields);
            $columns =  $db->numRows($fields);

            for ($i = 0; $i < $columns; $i++) {
                $field_array[] = $rs[$i]["Field"];
            }

            if (!in_array($key, $field_array)) {
                var_dump($key);
                $this->createField($key,$item);
            }


        }


    }
    public function createRelation($relation) {

        $db= Database::getDatabase();
        $table=strtolower(get_class($this->base));
        $rtable=strtolower(get_class($this->base))."_".$relation;
        $rs=$db->query("show tables like '".$rtable."'");

        if($db->hasRows($rs)==false) {
            $rs=$db->query("CREATE TABLE `".$rtable."`"."(id int(11)  NOT NULL AUTO_INCREMENT,`$table` int(11) NOT NULL,`$relation` int(11) NOT NULL, PRIMARY KEY (id))");

        }

    }

    public function createField($field,$property) {

        $db= Database::getDatabase();
        $table=strtolower(get_class($this->base));
        $many=false;

        foreach($property as $key=>$item) {

            switch ($key) {

                case 'required':
                    if($item==true)
                        $required=' NOT NULL ';
                    break;

                case 'type':

                    switch($item) {

                        case 'text':
                            $type='VARCHAR(255)';
                            break;

                        case 'number':
                            $type='int(11)';
                            break;

                        case 'decimal':
                            $type='decimal(6,2)';
                            break;

                        case 'longtext':
                            $type='text';
                            break;

                        case 'binary':
                            $type='blog';
                            break;

                    }
                    break;
                case 'reference':
                    $type='int(11)';
                    $required=' NOT NULL ';
                    $relation=$item;
                    break;

                case 'has':
                    switch($item) {

                        case 'one':
                            break;

                        case 'many':
                            $many=true;
                            break;

                        default:
                            break;
                    }
                    break;


            }

        }

        if($many) {

            $this->createRelation($relation);
        }

        $sql="ALTER TABLE `".$table."`"." ADD `$field` $type $required";
        if($required==true) {
            $sql.=' NOT NULL ';
        }


        $rs=$db->query($sql);

    }
    public function select($id,$column=null) {
        $db = Database::getDatabase();
        $attributes=get_object_vars($this->base);
        foreach($attributes as $name) {
            $this->base->name=NULL;
        }
        $table=strtolower(get_class($this->base));
        if(is_null($column)) $column = 'id';
        $column = $db->escape($column);

        $db->query("SELECT * FROM `{$table}` WHERE `$column` = :id: LIMIT 1", array('id' => $id));
        if($db->hasRows()) {
            $row = $db->getRow();
            $this->load($row);
            return true;
        }

        return false;
    }
    public function chain($obj=null) {

        $this->level=$this->level+1;
        if($obj==null) {
            $ref=$this->base;
        }
        else {
            $ref=$obj;

        }

        $this->obj[get_class($ref)]=get_class($ref);
        $attributes=get_object_vars($ref);
        $table=strtolower(get_class($ref));

        foreach($attributes as $key=>$name) {


            if(@get_class($table::$me[$key])=='Reference'&&!isset($this->obj[ucfirst($table::$me[$key]->relation)])&&$this->level<$this->depth) {

                $this->createJoin($table::$me[$key],$table,$table::$me[$key]->map);
                $ref=$table::$me[$key]->relation;
                $this->chain(new $ref);


            }

        }


        return $this;
    }

    function fetch($depth=null) {
        $this->depth=$depth;
        $this->chain();
        return $this;
    }
    function loads($obj) {

        $this->load[]="`".$obj."`.*";
        return $this;
    }

    function compile() {

        $objs=array();
        $table=strtolower(get_class($this->base));
        if(count($this->load)==0) {

            foreach($this->obj as $item) {
                $this->load[]= "`".strtolower($item)."`.*";
            }
        }
        $load=implode(',', $this->load);

        $this->query="SELECT $load FROM `{$table}`";
        $db = Database::getDatabase();
        foreach($this->join as $item ) {

            $this->query.=" ".$item." \n";
        }
        foreach($this->condition as $item ) {

            $this->query.=" ".$item." \n";
        }
       // echo $this->query;
        $db->query($this->query);
        $class_name=get_class($this->base);
        $rows=$db->getRows();

       // var_dump($this->obj);
        $objectArray=array();
       // $rows=array_unique($rows);
        foreach($rows as $key=>$item){

          
          // $rows[$key][key($item)];
            var_dump(key($rows));
            $ci=key($item);
            $c=new $ci;
            $attrib=get_object_vars($c);
            foreach($attrib as $vkey=>$var){
                
                $c->$vkey=$rows[$key][key($item)][$vkey];
            }
            $objectArray[md5(serialize($c))]=$c;
        }
        var_dump($objectArray);

        
        return $rows;

    }

    function createJoin($rObject,$table,$type='onetoone') {
        if($type=='onetoone') {
            $this->join[]="left join `".$rObject->relation."` on ".$table.".".$rObject->name."=".$rObject->relation.".".$rObject->relationField;
        }
        elseif($type=='manytomany') {
            $this->join[]="left join `".$table."_".$rObject->relation."` on ".$table.".".$this->idColumnName."= ".$table."_".$rObject->relation.".".$rObject->relationField;
            $this->join[]="left join `".$rObject->relation."` on ".$table."_".$rObject->relation.".".$rObject->relation."=".$rObject->relation.".".$rObject->relationField;
        }
        elseif($type=='onetomany') {
            $this->join[]="left join `".$rObject->relation."` on ".$table.".".$this->idColumnName."=".$rObject->relation.".".$rObject->relationField;

        }
    }


    function filter($filter,$type='AND',$compare=' = ') {


        if(count($this->condition)==0) {
            if(is_array($filter)) {
                $filter=$this->condition($filter,$type,$compare,FALSE);
            }
            $this->condition[]=" WHERE ". $filter;
        }
        else {
            if(is_array($filter)) {
                $filter=$this->condition($filter,$type,$compare);
            }
            $this->condition[]=" ".$filter;

        }

        return $this;
    }
    function limit($start=0,$offset='') {

        $this->limit="limit $start, $offset";

    }
    function condition($value,$type,$compare,$prefix=TRUE) {
        $message="";
        $msgArray=array();

        foreach($value as $key=>$item) {

            $msgArray[]="$key $compare'".$item."'";
        }
        $message=implode(" ".$type." ", $msgArray);
        if($prefix) {

            $message= $type." ".$message;
        }
        return $message;
    }
    public function query($sql,$args=null) {

        $db = Database::getDatabase();
        $db->query($sql,$args);
        return $db;

    }


    public function ok() {
        return !is_null($this->base->id);
    }

    public function save() {
        if(is_null($this->base->id))
            $this->insert();
        else
            $this->update();
        return $this->base->id;
    }

    public function insert($cmd = 'INSERT INTO') {
        $db = Database::getDatabase();
        $attributes=get_object_vars($this->base);
        $table=strtolower(get_class($this->base));
        if(count( $attributes) == 0) return false;

        $data = array();
        foreach($attributes as $k => $v)
            if(!is_null($v))
                $data[$k] = $db->quote($v);

        $columns = '`' . implode('`, `', array_keys($data)) . '`';
        $values = implode(',', $data);

        $db->query("$cmd `{$table}` ($columns) VALUES ($values)");
        $this->base->id = $db->insertId();
        return $this->base->id;
    }

    public function replace() {
        return $this->base->delete() && $this->base->insert();
    }

    public function update() {
        if(is_null($this->base->id)) return false;

        $db = Database::getDatabase();

        if(count($this->base->columns) == 0) return;

        $sql = "UPDATE {$this->base->tableName} SET ";
        foreach($this->base->columns as $k => $v)
            $sql .= "`$k`=" . $db->quote($v) . ',';
        $sql[strlen($sql) - 1] = ' ';

        $sql .= "WHERE `{$this->idColumnName}` = " . $db->quote($this->base->id);
        $db->query($sql);

        return $db->affectedRows();
    }

    public function delete() {
        if(is_null($this->base->id)) return false;
        $db = Database::getDatabase();
        $table=strtolower(get_class($this->base));
        $db->query("DELETE FROM `{$table}` WHERE `{$this->base->idColumnName}` = :id: LIMIT 1", array('id' => $this->base->id));
        return $db->affectedRows();
    }

    public function load($row,&$obj) {
        $attributes=get_object_vars($obj);

        foreach($row as $k => $v) {
            if($k == $this->idColumnName)
                $obj->id = $v;
            elseif(array_key_exists($k, $attributes))
                $obj->$k = $v;
        }

    }

    // Grabs a large block of instantiated $class_name objects from the database using only one query.
    // Note: Once PHP 5.3 becomes widespread, we can use get_called_class() to rewrite glob() and avoid
    // having to call it via DBObject rather than the actual class we're targeting.
    public static function glob($class_name, $sql = null, $extra_columns = array()) {
        $db = Database::getDatabase();

        // Make sure the class exists before we instantiate it...
        if(!class_exists($class_name))
            return false;

        $tmp_obj = new $class_name;

        // Also, it needs to be a subclass of DBObject...
        if(!is_subclass_of($tmp_obj, 'DBObject'))
            return false;

        if(is_null($sql))
            $sql = "SELECT * FROM `{$tmp_obj->tableName}`";

        $objs = array();
        $rows = $db->getRows($sql);
        foreach($rows as $row) {
            $o = new $class_name;
            $o->load($row);
            $objs[$o->id] = $o;

            foreach($extra_columns as $c) {
                $o->addColumn($c);
                $o->$c = isset($row[$c]) ? $row[$c] : null;
            }
        }
        return $objs;
    }

    public function addColumn($key, $val = null) {
        if(!in_array($key, array_keys($this->base->columns)))
            $this->base->columns[$key] = $val;
    }
}





