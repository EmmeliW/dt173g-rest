<?php

class Admin extends Database {
    // Properties
    private $username;
    private $password;


    public function get_all() {
        $query = "SELECT * FROM emmlan_portfolio.admin";

        // Prepare and execute statment
        $stmt = $this->connect()->prepare($query);
        $stmt->execute();
        return $stmt;
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        print $row;
    }

    public function login($username, $password) {
        $username = htmlspecialchars(strip_tags($username));
        $password = htmlspecialchars(strip_tags($password));

        $query = "SELECT * FROM emmlan_portfolio.admin WHERE username = '$username' AND password = '$password'";
        
        $result = $this->connect()->query($query);

        if($result->rowCount() == 1) {
               $_SESSION['username'] = $username;
               return true;
        } else {
            return false; 
        }
    }
}