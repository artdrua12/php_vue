<?php

$conn = new mysqli("localhost", "root", "", "crud_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "GET") {
    $sql = "SELECT * FROM albums";
    $result = $conn->query($sql);
    $data = array();

    while ($row = $result->fetch_array()) {
        array_push($data, $row);
    }
    $response = [
        "message" => "Альбомы успешно получены",
        "data" => $data,
        "status" => 200,
    ];
}

if ($requestMethod === "POST") {
    // $album_name = $_POST['album_name'];
    // $album_description = $_POST['album_description'];
    $data = json_decode(file_get_contents("php://input"), true);
    $album_name = $data["album_name"];
    $album_description = $data["album_description"];

    if (empty($album_name) || empty($album_description)) {
        $response = [
            "message" => "Заполните все поля",
            "status" => 400,
        ];
    } else {
        $sql = "INSERT INTO albums (album_name, album_description) VALUES ('$album_name', '$album_description')";
        $result = $conn->query($sql);
        $response = [
            "message" => "Альбом успешно создан",
            "data" => $result,
            "status" => 200,
        ];
    }
}

if ($requestMethod === "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);
    $album_name = $data["album_name"];
    $album_description = $data["album_description"];
    $album_id = $data["id"];

    if (empty($album_name) || empty($album_description)) {
        $response = [
            "message" => "Заполните все поля",
            "status" => 400,
        ];
    } else {
        $sql = "update albums set album_name='$album_name', album_description='$album_description' where id='$album_id'";
        $result = $conn->query($sql);
        $response = [
            "message" => "Альбом успешно обновлен",
            "data" => 'Album updated successfully',
            "status" => 200,
        ];

        header("Location: http://localhost:8081/");
    }
}

if ($requestMethod === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $album_id = $data["id"];

    $sql = "DELETE FROM albums WHERE id= $album_id";
    $result = $conn->query($sql);
    if ($result) {
        $response = [
            "message" => "Альбом удален",
            "data" => 'Album deleted successfully',
            "status" => 200,
        ];

    } else {
        $response = [
            "message" => "Не удалось удалить альбом",
            "status" => 400,
        ];
    }

}



$conn->close();
header("Content-Type: application/json");
// if (!headers_sent()) {
//     header('Access-Control-Allow-Origin: http://localhost:8081');
// }
header('Access-Control-Allow-Origin: null');


echo json_encode($response);
die();
?>