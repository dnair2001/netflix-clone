<?php
class VideoProvider{
    // only have static functions in this class so no need for constructor so we just pass in the connection variable
    public static function getUpNext($con, $currentVideo) {
        $query = $con->prepare("SELECT * FROM videos
                                WHERE entityId=:entityId AND id != :videoId
                                AND (
                                    (season = :season AND episode > :episode) OR season > :season
                                )
                                ORDER BY season, episode ASC LIMIT 1");
                                // We are selecting from the videos table of videos of time same entity and the video is not the current video we are currently on.
                                // In the and block, we get all the seasons of the current entity and get the episode that s greater than the current one we are one and 
                                // since the limit is just 1, we just return 1 episode. If there are no more episodes left in the season, we return the next season.
        $query->bindValue(":entityId", $currentVideo->getEntityId());
        $query->bindValue(":season", $currentVideo->getSeasonNumber());
        $query->bindValue(":episode", $currentVideo->getEpisodeNumber());
        $query->bindValue(":videoId", $currentVideo->getId());

        $query->execute();

        if ($query->rowCount() == 0) {
            $query = $con->prepare("SELECT * FROM videos
                                    WHERE season <= 1 AND episode <=1
                                    AND id != :videoId
                                    ORDER BY views DESC LIMIT 1");
            $query->bindValue(":videoId", $currentVideo->getId());
            $query->execute();
        }

        $row = $query->fetch(PDO::FETCH_ASSOC);
        return new Video($con, $row);
    }

    public static function getEntityVideoForUser($con, $entityId, $username){
        $query = $con->prepare("SELECT videoId FROM `videoprogress` 
                                INNER JOIN videos 
                                ON videoprogress.videoId = videos.id
                                WHERE videos.entityId = :entityId
                                AND videoProgress.username = :username
                                ORDER BY videoprogress.dateModified DESC
                                LIMIT 1");

        $query->bindValue(":entityId", $entityId);
        $query->bindValue(":username", $username);
        $query->execute();

        if($query->rowCount() == 0) {
            $query = $con->prepare("SELECT id FROM videos 
                                    WHERE entityId=:entityId
                                    ORDER BY season, episode ASC LIMIT 1");
            $query->bindValue(":entityId", $entityId);
            $query->execute();
        }
        return $query->fetchColumn(); // returns that column of value that we selected
    }
}

?>