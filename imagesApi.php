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
        array_push($data, [$image_data, $row['image_name'], $row['image_size'], $row['album_id']]);
    }

    if ($query) {
        $response = [
            "message" => "Картинка успешно получена",
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
    $image_size = $data["image_size"];
    $image_data = addslashes(file_get_contents($data['image_data']));
    // $img = addslashes(file_get_contents($_FILES['img_upload']['tmp_name']));


    $sql = "INSERT INTO images (image_name,album_id,image_size, image_data) VALUES ('$image_name','$album_id',' $image_size', '$image_data')";
    $result = $conn->query($sql);
    if ($result) {
        $response = [
            "message" => "Картинка добавлена",
            "data" => $result,
            "status" => 200,
        ];
    } else {
        $response = [
            "message" => "Не удалось добавить картинку",
            "status" => 400,
        ];
    }
}


if ($requestMethod === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $album_id = $data["id"];

    $sql = "DELETE FROM albums WHERE id= $album_id";
    $result = $conn->query($sql);
    if ($result) {
        $response = [
            "message" => "Картинка удалена",
            "data" => $result,
            "status" => 200,
        ];

    } else {
        $response = [
            "message" => "Не удалось удалить картинку",
            "status" => 400,
        ];
    }

    exit();
}




$conn->close();
// header("Content-type: image/jpeg");


header("Content-Type: application/json");
// if (!headers_sent()) {
//     header('Access-Control-Allow-Origin: http://localhost:8081');
// }


echo json_encode($response);
// echo mysql_result($result, 0);
// echo $result;
die();
?>