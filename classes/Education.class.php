<?php

class Education extends Database {
    // Course properties
    public $education_id;
    public $university;
    public $education_name;
    public $start_date;
    public $end_date;

    // Get all workposts
    public function getAll() {
        $query = 'SELECT * FROM emmlan_portfolio.education';

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $row = $stmt->rowCount();
        $data = array();

        if($row > 0) {
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $education_arr = array(
                    'education_id' => $education_id,
                    'university' => $university,
                    'education_name' => $education_name,
                    'start_date' => $start_date,
                    'end_date' => $end_date
                );
                array_push($data, $education_arr);
            }
        }
        return $data;
    }

    // Get single wopkposts
    public function getOne($id) {
        $query = "SELECT * FROM emmlan_portfolio.education WHERE education_id =" . $id;

        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no result, return empty array
        if(!$result) {
            $result = array();
        }
        return $result;
    }



    // Create new educationpost
   public function create() {
        $query = "INSERT INTO emmlan_portfolio.education 
        SET
            university = :university,
            education_name = :education_name,
            start_date = :start_date,
            end_date = :end_date";

        // Prepare statement
        $stmt = $this->connect()->prepare($query);

        // Clean up in data
        $this->university = htmlspecialchars(strip_tags($this->university));
        $this->education_name = htmlspecialchars(strip_tags($this->education_name));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
      
        // Bind data
        $stmt->bindParam(':university', $this->university);
        $stmt->bindParam(':education_name', $this->education_name);
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

   // Update educationpost
    public function update($id) {
        $query = 'UPDATE emmlan_portfolio.education
            SET
                university = :university,
                education_name = :education_name,
                start_date = :start_date,
                end_date = :end_date
            WHERE
                  education_id = :education_id';

        $stmt = $this->connect()->prepare($query);

        // Clean up in data
        $this->university = htmlspecialchars(strip_tags($this->university));
        $this->education_name = htmlspecialchars(strip_tags($this->education_name));
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));

        // Bind data to params
        $stmt->bindParam(':education_id', $id);
        $stmt->bindParam(':university', $this->university);
        $stmt->bindParam(':education_name', $this->education_name);
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
        $query = "DELETE FROM emmlan_portfolio.education WHERE education_id = $id";
        $stmt = $this->connect()->prepare($query);

        if($stmt->execute()) {
            return true;
        }
        printif('Error: %s.\n', $stmt->error);
        return false;    
    }
}