<?php
require_once("../includes/config.php");

if(isset($_POST["videoId"]) && isset($_POST["username"])) {
    //insert query
    $query = $con->prepare("SELECT * FROM videoprogress 
                            WHERE username=:username AND videoId=:videoId");
    $query->bindValue(":username", $_POST["username"]);
    $query->bindValue(":videoId", $_POST["videoId"]);

    $query->execute();

    // check to see if rows exist on the table
    if($query->rowCount() == 0) {
        // insert rows
        $query = $con->prepare("INSERT INTO videoprogress (username, videoId)
                                VALUES(:username, :videoId)");
        $query->bindValue(":username", $_POST["username"]);
        $query->bindValue(":videoId", $_POST["videoId"]);
    
        $query->execute();
        
    }
}
else {
    echo "No videoId or username passed into file";
}
?>