<?php

$conn = new mysqli("localhost", "root", "", "crud_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "GET") {

    $sql = "SELECT image FROM images WHERE id=1";
    $result = $conn->query($sql);
    $data = array();

    while ($row = $result->fetch_array()) {
        array_push($data, $row);
    }

    $response = [
        "message" => "Images read Successfully",
        "data" => $data,
        "status" => 200,
    ];
}

if ($requestMethod === "POST") {
    // $album_name = $_POST['album_name'];
    // $album_coment = $_POST['album_coment'];
    $data = json_decode(file_get_contents("php://input"), true);
    $image_name = $data["image_name"];
    $imageType = $data["image_type"];

    $image = $data["base64"];


    $sql = "INSERT INTO albums (image_name, image) VALUES ('$image_name', '$image')";
    $result = $conn->query($sql);
    if ($result) {
        $response = [
            "message" => "Images received!",
            "data" => $result,
            "status" => 200,
        ];
    } else {
        $response = [
            "message" => "Could not add album",
            "status" => 400,
        ];
    }
}

if ($requestMethod === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    print_r(json_encode($data));
    $album_name = $data["album_name"];
    $album_coment = $data["album_coment"];
    $album_id = $data["id"];




    if (empty($album_name) || empty($album_coment)) {
        $response = [
            "message" => "Please fill out all the required fields",
            "status" => 400,
        ];
        exit();
    } else {
        $sql = "update albums set album_name='$album_name', album_coment='$album_coment' where id='$album_id'";
        $result = $conn->query($sql);
        if ($result) {
            $response = [
                "message" => "Album updated successfully",
                "data" => $result,
                "status" => 200,
            ];

            header("Location: http://localhost:8081/");
            exit();

        } else {
            $response = [
                "message" => "Could not add album",
                "status" => 400,
            ];
        }
    }
}

if ($requestMethod === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $album_id = $data["id"];

    $sql = "DELETE FROM albums WHERE id= $album_id";
    $result = $conn->query($sql);
    if ($result) {
        $response = [
            "message" => "Album deleted successfully",
            "data" => $result,
            "status" => 200,
        ];

    } else {
        $response = [
            "message" => "Could not add album",
            "status" => 400,
        ];
    }

    exit();
}




$conn->close();
header("Content-type: image/jpeg");
// header("Content-Type: application/json");
// if (!headers_sent()) {
//     header('Access-Control-Allow-Origin: http://localhost:8081');
// }


// echo json_encode($response);
// echo mysql_result($result, 0);
echo $result;
die();
?>