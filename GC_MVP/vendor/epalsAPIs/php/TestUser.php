<?php 

require_once("user.php");

$email = "schaudhri1217@epals.com";
$u = new User();
$res = $u->userExists($email);
print("My res is (int)$res");
?>
