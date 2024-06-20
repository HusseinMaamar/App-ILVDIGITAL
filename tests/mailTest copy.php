<?php
use controller\Controller;
require_once '../Autoload.php';

$c = new Controller ;
$c->testMail('hocinesadda14@gmail.com' , 'cc'  , 'test' , 'f@gg.fr')
?>