<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();

if ($conn === null) {
    die("Failed to connect to the database");
}

$user = print_r(file_get_contents('php://input'));
$method = $_SERVER['REQUEST_METHOD'];
switch($method){
    case "GET":
        $sql = "SELECT * FROM users";
        $path = explode('/',$_SERVER['REQUEST_URI']);

        if(isset($path[3]) && is_numeric($path[3])){
            $sql .= " WHERE id = :id;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $users = $stmt->fetch(PDO::FETCH_ASSOC);
        }else{
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        echo json_encode($users);
        break;

    case "POST" :
        $user = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO users( name, email, mobile, created_at) VALUES( :name, :email ,:mobile, :created_at)";
        $stmt = $conn->prepare($sql);
        $created_at = date('Y-m-d');
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':created_at', $created_at);
        if($stmt->execute()){
            $response = ['status' => 1, 'message' => 'Record created successfully'];
        }else{
            $response = ['status' => 0, 'message' => 'Fail to create record'];
        }
        echo json_encode($response);
        break;
    
    case "PUT" :
        $user = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE users SET name=:name, email=:email, mobile=:mobile, updated_at = :updated_at WHERE id=:id";
        $stmt = $conn->prepare($sql);
        $updated_at = date('Y-m-d');
        $stmt->bindParam(':id', $user->id);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':email', $user->email);
        $stmt->bindParam(':mobile', $user->mobile);
        $stmt->bindParam(':updated_at', $updated_at);
        if($stmt->execute()){
            $response = ['status' => 1, 'message' => 'Record updated successfully'];
        }else{
            $response = ['status' => 0, 'message' => 'Fail to update record'];
        }
        echo json_encode($response);
        break;

    case "DELETE":
        $sql = "DELETE FROM users WHERE id=:id";
        $path = explode('/',$_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);
        $stmt->execute();
        
        if($stmt->execute()){
            $response = ['status' => 1, 'message' => 'Record deleted successfully'];
        }else{
            $response = ['status' => 0, 'message' => 'Fail to delete record'];
        }
        echo json_encode($response);
        break;
}