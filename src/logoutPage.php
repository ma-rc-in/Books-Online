<?php require 'databaseConnection.php'?>
<?php
/* The below code is used to destroy the current session whene this file is trigerred */
  session_start();
  unset($_SESSION["memberID"]);
  session_destroy();
  header('Location:index.php');
?>
