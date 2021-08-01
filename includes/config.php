<?php
ob_start(); //turns on output buffering. Waits until all of the php has executed until it outputs it onto the page
session_start(); // tells if the user has logged in or not

date_default_timezone_set("Europe/London");


//Connect to Database
//try to connect to database, it it doesn't, go to catch block
// PDO means PHP Data Object

try {
    $con = new PDO("mysql:dbname=divya-netflix-clone;host=localhost", "root", "");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}

//listening for variable of type PDOException
catch (PDOException $e) {
    exit("Connection failed: " . $e->getMessage());
}
?>

