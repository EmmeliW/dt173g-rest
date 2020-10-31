<?php

class Work extends Database {
    // Course properties
    public $work_id;
    public $workplace;
    public $title;
    public $start_date;
    public $end_date;

    // Get all workposts
    public function getAll() {
        $query = 'SELECT * FROM emmlan_portfolio.work';

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $row = $stmt->rowCount();
        $data = array();

        if($row > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $work_arr = array(
                    'work_id' => $work_id,
                    'workplace' => $workplace,
                    'title' => $title,
                    'start_date' => $start_date,
                    'end_date' => $end_date
                );
                array_push($data, $work_arr);
            }
        }
        return $data;
    }

    // Get single wopkposts
    public function getOne($id) {
        $query = "SELECT * FROM emmlan_portfolio.work WHERE work_id =" . $id;

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no result, return empty array
        if(!$result) {
            $result = array();
        }
        return $result;
    }



    // Create new workpost
   public function create() {
        $query = "INSERT INTO emmlan_portfolio.work 
        SET
            workplace = :workplace,
            title = :title,
            start_date = :start_date,
            end_date = :end_date";

        // Prepare statement
        $stmt = $this->connect()->prepare($query);

        // Clean up in data
        $this->workplace = htmlspecialchars(strip_tags($this->workplace));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
      
        // Bind data
        $stmt->bindParam(':workplace', $this->workplace);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':start_date', $this->start_date);
        $stmt->bindParam(':end_date', $this->end_date);

        // Try to execute the statement
        if($stmt->execute()) {
            return true;
        }
        // Message if fail
        printif('Error: %s.\n', $stmt->error);
        return false;
   }

   // Update workpost
    public function update($id) {
        $query = 'UPDATE emmlan_portfolio.work
            SET
                workplace = :workplace,
                title = :title,
                start_date = :start_date,
                end_date = :end_date
            WHERE
                  work_id = :work_id';

        $stmt = $this->connect()->prepare($query);

        // Clean up in data
        $this->workplace = htmlspecialchars(strip_tags($this->workplace));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));

        // Bind data to params
        $stmt->bindParam(':work_id', $id);
        $stmt->bindParam(':workplace', $this->workplace);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':start_date', $this->start_date);
        $stmt->bindParam(':end_date', $this->end_date);

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
        $query = "DELETE FROM emmlan_portfolio.work WHERE work_id = $id";
        $stmt = $this->connect()->prepare($query);

        if($stmt->execute()) {
            return true;
        }
        printif('Error: %s.\n', $stmt->error);
        return false;    
    }
}