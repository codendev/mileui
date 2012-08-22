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
 * @link		http://www.codendev.com
 * @since		Version 1.0
 * @filesource
 */

require_once 'autoload.php';



$page= new Page();
$page->block= new Block();

$fallback=new Fallback();
$session=new Session();
$action =new Action();
$common=new Common();
$eventLog= new EventLog();
$view =new View();

$page->block->attach($action);
$page->block->attach($view);

$page->attach($fallback);
$page->attach($session);
$page->attach($action);
$page->attach($common);
$page->attach($eventLog);
$page->attach($view);
$page->attach($session);


$page->create();
$page->publish(); 

?>
