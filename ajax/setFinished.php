<?php
// set the finished column on the SQL table to 1 and set progress to 0 after the finish watching the video.

require_once("../includes/config.php");

if(isset($_POST["videoId"]) && isset($_POST["username"])) {
    //insert query
    $query = $con->prepare("UPDATE videoprogress SET finished=1, progress=0
                            WHERE username=:username AND videoId=:videoId");
    $query->bindValue(":username", $_POST["username"]);
    $query->bindValue(":videoId", $_POST["videoId"]);

    $query->execute();
}
else {
    echo "No videoId or username passed into file";
}

?>