<?php
require '../classes/Database.class.php';
require '../classes/Website.class.php';

// Headers
header('Access-Control-Allow-Origin: https://www.emmlan.se');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Typ, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$method = $_SERVER['REQUEST_METHOD'];

// If id is sent, get id
if(isset($_GET['id'])) {
   $id = $_GET['id'];
}

// DB connenction
$database = new Database();
$db = $database->connect();

// Instansiate class
$website = new Website();

// Look for GET, POST, PUT or DELETE
switch($method) {
   case 'GET':
      if(isset($id)) { // Check if id is sent
         $result = $website->getOne($id);
      } else {
         $result = $website->getAll();
      }

      if(sizeof($result) > 0) { // Is thete any results?
         http_response_code(200); 
      } else {
         http_response_code(404); 
         $result = array("message" => "Kunde ej hitta någon data");
      }
      
      break;
   case "POST":
      $data = json_decode(file_get_contents("php://input"));

      $website->title = $data->title;
      $website->url = $data->url;
      $website->repo = $data->repo;
      $website->description = $data->description;
      $website->img = $data->img;

      if($website->create()) {
         http_response_code(201);
         $result = array("message" => "Ny webbplats tillagt"); 
      } else {
         http_response_code(503);
         $result = array("message" => "Kunde ej lägga till");
      }

      break;
   case "PUT":
      if(!isset($id)) {
         http_response_code(510);
         $result = array("message" => "Id krävs");
      } else{
         $data = json_decode(file_get_contents("php://input"));

         $website->title = $data->title;
         $website->url = $data->url;
         $website->repo = $data->repo;
         $website->description = $data->description;
         $website->img = $data->img;

         if(!$website->update($id)) {
            http_response_code(503);
            $result = array("message" => "Kunde ej uppdatera");
         } else {
            http_response_code(200);
            $result = array("message" => "Uppdaterat!");
         }
      }
      
      break;
   case "DELETE":
      if(isset($id)) {
         if($website->delete($id)) {
            http_response_code(200);
            $result = array("message" => "Raderad");
         } else {
            http_response_code(503);
            $result = array("message" => "Kunde ej radera");
         }
      } else {
         http_response_code(510);
         $result = array("message" => "Id krävs");
      }
      break;
}

// Print result
echo json_encode($result);