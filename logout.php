<?php 

session_start();
unset($_SESSION);
session_destroy();
header("location: auth/login.html");
exit;

?>