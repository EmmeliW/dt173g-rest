<?php
require '../classes/Database.class.php';
require '../classes/Work.class.php';

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
$work = new Work();

// Look for GET, POST, PUT or DELETE
switch($method) {
   case 'GET':
      if(isset($id)) { // Check if id is sent
         $result = $work->getOne($id);
      } else {
         $result = $work->getAll();
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

      $work->workplace = $data->workplace;
      $work->title = $data->title;
      $work->start_date = $data->start_date;
      $work->end_date = $data->end_date;

      if($work->create()) {
         http_response_code(201);
         $result = array("message" => "Nytt jobb tillagt"); 
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

         $work->workplace = $data->workplace;
         $work->title = $data->title;
         $work->start_date = $data->start_date;
         $work->end_date = $data->end_date;

         if(!$work->update($id)) {
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
         if($work->delete($id)) {
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