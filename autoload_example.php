
<?php 
function __autoload($class_name) 
{
    require_once $class_name.".php";
}

$class1 = new class1;
$class2 = new class2;
$class4 = new class4;
$class3 = new class3;

echo $class1->a."<br>";
echo $class2->a."<br>";
echo $class3->a."<br>";
echo $class4->a."<br>";