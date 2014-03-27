<?php
session_start();
return ;
if($_SERVER['REQUEST_URI'] == '/' || 
   $_SERVER['REQUEST_URI'] == '/application/Index/login' || 
   $_SERVER['REQUEST_URI'] == "/provisioning/Provisioning/index" ||
   $_SERVER['REQUEST_URI'] == "/provisioning/index.phtml{*}" ){
    return ;
}
if (!isset($_SESSION['loginSession'])) {
    echo "<p align=center>";
    echo "<font color=#ff0000 size=5><strong><big>";
    echo "You should login first !</br> <a href='http://localhost'>Login</a>";
    echo "</big></strong></font></p>";
    exit();
}
?>
