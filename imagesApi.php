<?php

$conn = new mysqli("localhost", "root", "", "crud_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "GET") {
    $data = array();
    $query = $conn->query("SELECT * FROM images WHERE album_id = $_GET[id]");
    while ($row = $query->fetch_assoc()) {
        $image_data = base64_encode($row['image_data']);
        array_push($data, [$image_data, $row['image_name'], $row['image_like'], $row['album_id']]);
    }

    if ($query) {
        $response = [
            "message" => "Запрос на картинки успешно выполнен",
            "data" => $data,
            "status" => 200,
        ];
    } else {
        $response = [
            "message" => "Не удалось получить картинку",
            "status" => 400,
            "data" => 'no data',
        ];
    }
}

if ($requestMethod === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $image_name = $data["image_name"];
    $album_id = $data["album_id"];
    $image_like = $data["image_like"];
    $image_data = addslashes(file_get_contents($data['image_data']));


    $sql = "INSERT INTO images (image_name,album_id,image_like, image_data) VALUES ('$image_name','$album_id',' $image_like', '$image_data')";
    $result = $conn->query($sql);

    $response = [
        "message" => "Картинка добавлена",
        "data" => "Картинка добавлена",
        "status" => 200,
    ];

}


if ($requestMethod === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $image_name = $data["image_name"];
    $album_id = $data["album_id"];

    $sql = "DELETE FROM images WHERE album_id = '$album_id' AND image_name = '$image_name'";
    $result = $conn->query($sql);

    if ($result) {
        $response = [
            "message" => "Картинка удалена",
            "data" => "Картинка удалена",
            "status" => 200,
        ];
    } else {
        $response = [
            "message" => "Не удалось удалить картинку",
            "status" => 400,
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