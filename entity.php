<?php
require_once("includes/header.php");

//get Id
if(!isset($_GET["id"])) {
    ErrorMessage::show("No ID passed into page");
}

$entityId = $_GET["id"];
$entity = new Entity($con, $entityId);

$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createPreviewVideo($entity);

//create object
$seasonProvider  =new SeasonProvider($con, $userLoggedIn);
echo $seasonProvider->create($entity);

$categoryContainers  =new CategoryContainers($con, $userLoggedIn);
echo $categoryContainers->showCategory($entity->getCategoryId(), "You might also like");


?>
