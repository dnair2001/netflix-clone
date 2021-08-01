<?php
// set the finished column on the SQL table to 1 and set progress to 0 after the finish watching the video.

require_once("../includes/config.php");
require_once("../includes/classes/SearchResultsProvider.php");
require_once("../includes/classes/EntityProvider.php");
require_once("../includes/classes/Entity.php");
require_once("../includes/classes/PreviewProvider.php");

if(isset($_POST["term"]) && isset($_POST["username"])) {
    $srp = new SearchResultsProvider($con, $_POST["username"]);
    echo $srp->getResults($_POST["term"]);
}
else {
    echo "No term or username passed into file";
}

?>