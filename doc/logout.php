<?php session_start(); ?>
<?php
unset($_SESSION['UserName']);
header("Location:../index.php");
?>
