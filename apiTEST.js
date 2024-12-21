<? php

$conn = new mysqli("localhost", "root", "", "crud_db");
if ($conn -> connect_error) {
    die("Connection failed: ".$conn -> connect_error);
}

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "POST") {
    $data = file_get_contents("php://input"), true;
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
        $result = $conn -> query($sql);
        $response = [
            "message" => "Альбом успешно обновлен",
            "data" => 'Album updated successfully',
            "status" => 200,
        ];

        header("Location: http://localhost:8081/");
    }
}




$conn -> close();
header("text/plain");
// if (!headers_sent()) {
//     header('Access-Control-Allow-Origin: http://localhost:8081');
// }


echo $response;
die();
?>