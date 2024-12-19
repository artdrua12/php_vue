<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Загрузка картинки в БД</title>
</head>

<body>
    <!-- <form action="index.php" method="post" enctype="multipart/form-data">
        <p>Загрузить картику</p>
        <input type="file" name="img_upload"><input type="submit" name="upload" value="Загрузить">
        <?php
        $conn = new mysqli("localhost", "root", "", "crud_db");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if (isset($_POST['upload'])) {
            if (!empty($_FILES['img_upload']['tmp_name'])) {
                $image_name = $_FILES['img_upload']['name'];
                $image_data = addslashes(file_get_contents($_FILES['img_upload']['tmp_name']));
                $image_size = $_FILES['img_upload']['size'] / 1024;
                $image_type = $_FILES['img_upload']['type'];
                $conn->query("INSERT INTO images (image_name,image_size, image_type,image_data) VALUES ($image_name,$image_size,$image_type,$image_data)");
            }
        }
        ?>

    </form> -->


    <?php
    $sql = "SELECT * FROM images ";
    $result = $conn->query($sql);
    $data = array();

    while ($row = $result->fetch_array()) {
        array_push($data, $row);
        echo "<b>" . $image_name . "</b> (" . $image_size . "kB.)"; ?> <br />
        <img src="data:image/jpeg;base64, <?= base64_encode($row['image_data']) ?>" alt="" width="100"><br />
        <br />
    <?php }

    ?>


</body>

</html>