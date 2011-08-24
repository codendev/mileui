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
    private $map=array();
    private $start=0;
    private $offset=1;
    private $object_count=0;



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
    /**
	 * @param id
	 * @param column
         * @return bool Returns true on success or false on failure.
    */
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
    private function chain($obj=null) {

        $this->level=$this->level+1;
        if($obj==null) {
            $ref=$this->base;
        }
        else {
            $ref=$obj;

        }

        $this->obj[strtolower(get_class($ref))]=get_class($ref);
        $attributes=get_object_vars($ref);
        $table=strtolower(get_class($ref));

        foreach($attributes as $key=>$name) {


            if(@get_class($table::$me[$key])=='Reference'&&!isset($this->obj[strtolower($table::$me[$key]->relation)])&&$this->level<$this->depth) {

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

        if(isset($this->limit))
            $this->query.=" ".$this->limit;

        $db->query($this->query);
        $class_name=get_class($this->base);
        $rows=$db->getRows();

        // var_dump($this->obj);
        $objectArray=array();
        // $rows=array_unique($rows);
        foreach($rows as $key=>$item) {


            // $rows[$key][key($item)];
            $ci=key($item);
            $c=new $ci;
            $attrib=get_object_vars($c);
            foreach($attrib as $vkey=>$var) {

                $c->$vkey=$rows[$key][key($item)][$vkey];
            }
            $objectArray[md5(serialize($c))]=$c;
        }
        //var_dump($objectArray);


        return $rows;

    }
    function object_count(){

        return $this->object_count;
    }
    function count() {
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
        $db->query($this->query);
        return $db->numRows();
    }
    function object_compile() {

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
       
        $db->query($this->query);
        $class_name=get_class($this->base);
        $rows=$db->getRows();
      
       
        // var_dump($this->obj);
        $objectArray=array();
        // $rows=array_unique($rows);
        // var_dump($rows);;exit;
        foreach($rows as $item) {

            // var_dump($item); echo "<br><br>";

        }
        $obj=$this->mapping($this->base, $rows);
        //  var_dump($obj);

        $this->object_count=sizeof($obj);
        if(isset($this->limit))
        {
         
             $obj=array_slice($obj,$this->start,$this->offset);
        }
        return $obj;


    }
    function mapping($object,$resultset,$condition=null) {

        $c= $object;
        $objectArray=array();
        $tempArray=array();
        $name=strtolower(get_class($object));
        $this->map[]=$name;
        $this->map=array_unique($this->map);
        $attrib=get_object_vars($c);
        //if($name=="object"){  var_dump($condition);return null;}

        foreach($resultset as $key=>$item) {
            if(!array_key_exists($name, $resultset[$key])) {
                return null;
            }
            foreach($attrib as $vkey=>$var) {


                if(@get_class(@$c::$me[$vkey])=="Type") {


                    if($condition!=NULL) {

                        if($resultset[$key][$name][key($condition)]==$condition[key($condition)]) {
                            $c->$vkey=$resultset[$key][$name][$vkey];
                            if($name=="objectfield") {
                                // var_dump($c);;
                            }

                        }else {
                            if($name=="objectfield" and $c->name==null) {
                                //    var_dump($c);;
                            }
                        }
                    }
                    else {

                        $c->$vkey=$resultset[$key][$name][$vkey];
                        if($name=="user" ) {
                            //var_dump($c);;echo "<br><br>";

                        }
                    }

                }
                elseif(@get_class(@$c::$me[$vkey])=="Reference") {

                    //
                    //var_dump("Relation::".array_search($c::$me[$vkey]->relation, $this->map));
                    if($c::$me[$vkey]->map=="onetoone"&& (in_array($c::$me[$vkey]->relation, $this->map)==false || array_search($c::$me[$vkey]->relation, $this->map)>array_search($name, $this->map))) {
                        $cond1=array('id'=>$resultset[$key][$name][$vkey]);
                        $ores=$this->mapping(new $vkey(), $resultset,$cond1);
                        if(isset($ores[0])) {
                            $c->$vkey=$ores[0];
                           
                        }
                    }
                    elseif($c::$me[$vkey]->map=="onetomany"&&(in_array($c::$me[$vkey]->relation, $this->map)==false || array_search($c::$me[$vkey]->relation, $this->map)>array_search($name, $this->map))) {

                        $cond2=array($name=>$resultset[$key][$name]["id"]);

                        $or=$this->mapping(new $c::$me[$vkey]->relation, $resultset,$cond2);

                        $c->$vkey=$or;
                        // if($c->$vkey==null){ echo $vkey;}
                    }
                    else{
                       
                        unset($c->$vkey);
                    }




                }

            }  if



            ($name=="objectfield" and $c->name==null) {
                // var_dump($c);;

            }
            $id=$this->idColumnName;
            $c_clone=clone $c;
            if(!array_key_exists(md5($c_clone->id), $tempArray)&&$c->$id!=null) {
                {
                    $tempArray[md5($c_clone->id)]=md5(serialize( $c_clone));
                    $objectArray[]= $c_clone;
                }
            }
            // if($name=="object" ){  var_dump($objectArray[md5(serialize($c))]); echo "::".md5(serialize($c));echo "<br><br>";}
            // if($name=="group"){ var_dump($objectArray); echo "<br><br>";}


        }

        //if($name=="object" ){  echo var_dump($objectArray);;echo "<br><br>";}
        return $objectArray;
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
    function limit($start=0,$offset=1) {

        $this->start=$start;
        $this->offset=$offset;
        $this->limit="limit ($start, $offset)";
        return $this;
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

    public function save($obj=null) {
        if(isset($obj)){
             if(is_null($obj->id))
            $this->insert($obj);
        else
            $this->update($obj);
        return $obj->id;
        }
        else{
        if(is_null($this->base->id))
            $this->insert($this->base);
        else
            $this->update($this->base);
        return $this->base->id;
        }
    }

    public function insert($obj) {
        $db = Database::getDatabase();
        $attributes=get_object_vars($obj);
        $table=strtolower(get_class($obj));
        if(count( $attributes) == 0) return false;
                 foreach($attributes as $k => $v){
            if($v==null){

                 unset ($attributes[$k]);
            }
            elseif(is_array($attributes[$k])){

                foreach($attributes[$k] as $item){

                    $this->save($item);
                }
                  unset($attributes[$k]);
            }
            elseif(is_object($attributes[$k])){

                 $attributes[$k]=$this->save($attributes[$k]);
                   
            }

         }
        $cmd = 'INSERT INTO';
        $data = array();
        foreach($attributes as $k => $v)
            if(!is_null($v))
                $data[$k] = $db->quote($v);

        $columns = '`' . implode('`, `', array_keys($data)) . '`';
        $values = implode(',', $data);

        $db->query("$cmd `{$table}` ($columns) VALUES ($values)");
        $obj->id = $db->insertId();
        return $obj->id;
    }

    public function replace() {
        return $this->base->delete() && $this->base->insert();
    }

    public function update($obj) {
        if(is_null($obj->id)) return false;

        $db = Database::getDatabase();
        $attrib=get_object_vars($obj);
        if(count($attrib) == 0) return;
         foreach($attrib as $k => $v){
            if($v==null){
                 unset ($attrib[$k]);
            }
            elseif(is_array($attrib[$k])){
              
                foreach($attrib[$k] as $item){
                    
                    $this->save($item);
                }
                unset($attrib[$k]);
            }
            elseif(is_object($attrib[$k])){

               $attrib[$k]= $this->save($attrib[$k]);
                
            }

         }
        $table=strtolower(get_class($obj));
      
        $sql = "UPDATE `{$table}` SET ";
        foreach($attrib as $k => $v)
            $sql .= "`$k`=" . $db->quote($v) . ',';
        $sql[strlen($sql) - 1] = ' ';
         
        $sql .= "WHERE `{$this->idColumnName}` = " . $db->quote($obj->id);
        
        $db->query($sql);

        return $db->affectedRows();
    }

    public function delete() {
        if(is_null($this->base->id)) return false;
        $db = Database::getDatabase();
        $table=strtolower(get_class($this->base));
        $db->query("DELETE FROM `{$table}` WHERE `{$this->idColumnName}` = :id: LIMIT 1", array('id' => $this->base->id));
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





