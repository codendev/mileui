<?

require_once 'autoload.php';

// Load our config settings


//var_dump(get_object_vars($x));


//class Person{
//
//   public $name;
//   public $age;
//   public $address;
//
//
//   function  __construct() {
//
//       //get_object_vars($this);
//       $name=new Field('Name',23);
//       $age=new Field('Age',23);
//       $address=new Field('Address',23);
//   }
//
//
//
//}
// $x= new Person();


$usr= new User();

$dbObj=FactoryMethod::data('mysql', $usr);

$condition=array('user.id'=>'1','object.name'=>'product');

$res=$dbObj->fetch(4)->compile();
foreach($res as $item){
//var_dump($item); echo "<br><br><br>";
}


?>