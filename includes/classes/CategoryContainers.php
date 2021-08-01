<?php
class CategoryContainers {

    private $con;
    private $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }

    public function showAllCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, true, true);
        }

        return $html . "</div>";
    }

    // for shows.php
    public function showTVShowCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                    <h1>TV Shows</h1>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, true, false); //false for the movies categories since we just want to show tv shows
        }

        return $html . "</div>";
    }

    // for movies.php
    public function showMovieCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                    <h1>Movies</h1>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, null, false, true); //false for the tv shows categories since we just want to show movies
        }

        return $html . "</div>";
    }

    // This will show a category that we give it. Will show all the entities that fall under that category given
    public function showCategory($categoryId, $title = null) {
        $query = $this->con->prepare("SELECT * FROM categories WHERE id=:id");
        $query->bindValue(":id", $categoryId);
        $query->execute();

        $html = "<div class='previewCategories noScroll'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHtml($row, $title, true, true);
        }

        return $html . "</div>";
    }

    private function getCategoryHtml($sqlData, $title, $tvShows, $movies) {
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        // if tshows and movies are both true get both of them
        if($tvShows && $movies) {
            $entities = EntityProvider::getEntities($this->con, $categoryId, 30);
        }
        else if ($tvShows) {
            //Get tv show entities
            $entities = EntityProvider::getTVShowEntities($this->con, $categoryId, 30);
        }
        else {
            // Get movie entities
            $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 30);
        }

        if(sizeof($entities) == 0) {
            return;
        }

        $entitiesHtml = "";
        //create object from constructor
        $previewProvider = new PreviewProvider($this->con, $this->username);

        foreach($entities as $entity) {
            $entitiesHtml .= $previewProvider->createEntityPreviewSquare($entity);
        }
        
        return "<div class='category'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>

                    <div class='entities'>
                        $entitiesHtml
                    </div>
                </div>";
    }

}

?>