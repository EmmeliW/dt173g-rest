<?php

class Website extends Database {
    // Course properties
    public $website_id;
    public $title;
    public $url;
    public $repo;
    public $description;
    public $img;

    // Get all workposts
    public function getAll() {
        $query = 'SELECT * FROM emmlan_portfolio.website';

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $row = $stmt->rowCount();
        $data = array();

        if($row > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $web_arr = array(
                    'website_id' => $website_id,
                    'title' => $title,
                    'url' => $url,
                    'repo' => $repo,
                    'description' => $description,
                    'img' => $img
                );
                array_push($data, $web_arr);
            }
        }
        return $data;
    }

    // Get single wopkposts
    public function getOne($id) {
        $query = "SELECT * FROM emmlan_portfolio.website WHERE website_id =" . $id;

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no result, return empty array
        if(!$result) {
            $result = array();
        }
        return $result;
    }



    // Create new websitepost
   public function create() {
        $query = "INSERT INTO emmlan_portfolio.website 
        SET
            title = :title,
            url = :url,
            repo = :repo,
            description = :description,
            img = :img";

        // Prepare statement
        $stmt = $this->connect()->prepare($query);

        // Clean up in data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->repo = htmlspecialchars(strip_tags($this->repo));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->img = htmlspecialchars(strip_tags($this->img));
      
        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':url', $this->url);
        $stmt->bindParam(':repo', $this->repo);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':img', $this->img);

        // Try to execute the statement
        if($stmt->execute()) {
            return true;
        }
        // Message if fail
        printif('Error: %s.\n', $stmt->error);
        return false;
   }

   // Update websitepost
    public function update($id) {
        $query = 'UPDATE emmlan_portfolio.website
            SET
                title = :title,
                url = :url,
                repo = :repo,
                description = :description,
                img = :img
            WHERE
                  website_id = :website_id';

        $stmt = $this->connect()->prepare($query);

        // Clean up in data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->url = htmlspecialchars(strip_tags($this->url));
        $this->repo = htmlspecialchars(strip_tags($this->repo));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->img = htmlspecialchars(strip_tags($this->img));

        // Bind data to params
        $stmt->bindParam(':website_id', $id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':url', $this->url);
        $stmt->bindParam(':repo', $this->repo);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':img', $this->img);

        // Execute
        if($stmt->execute()) {
            return true;
        }
        // Message if fail
        printif('Error: %s.\n', $stmt->error);
        return false;
   }

    // Delete
    public function delete($id) {
        $query = "DELETE FROM emmlan_portfolio.website WHERE website_id = $id";
        $stmt = $this->connect()->prepare($query);

        if($stmt->execute()) {
            return true;
        }
        printif('Error: %s.\n', $stmt->error);
        return false;    
    }
}