<?php
/* The below code is used for establishing connection with the database */
function connectTodatabase() {
  try {
    $connectionDatabase = new PDO("mysql:host=localhost;dbname=databaseforwebmodule", 'root', '');
    return $connectionDatabase;
  }
  catch (Exception $exception) {
    header('location:index.php');
  }
}?>
