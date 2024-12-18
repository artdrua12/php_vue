<?php

$conn = new mysqli("localhost", "root", "", "crud_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "GET") {

    $data = array();
    $query = $conn->query("SELECT * FROM images ORDER BY id DESC");
    while ($row = $query->fetch_assoc()) {
        $image_data = base64_encode($row['image_data']);
        array_push($data, [$image_data, $row['image_name'],$row['image_size'],$row['image_type']]);
    }

    if ($query) {
        $response = [
            "message" => "Images read successfully",
            "data" => $data,
            "status" => 200,
        ];
    } else {
        $response = [
            "message" => "Could not read images",
            "status" => 400,
            "data" => 'no data',
        ];
    }
}


$conn->close();
header("Content-Type: application/json");
// if (!headers_sent()) {
//     header('Access-Control-Allow-Origin: http://localhost:8081');
// }


echo json_encode($response);
die();
?>