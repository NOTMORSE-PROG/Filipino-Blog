<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "FilipinoBlog"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $image = $_FILES['image']['tmp_name'];
    $imageContent = addslashes(file_get_contents($image));

 
    $imageType = $_FILES['image']['type'];
    if (strpos($imageType, 'image/') !== 0) {
        echo "Uploaded file is not an image.";
        exit;
    }


    $sql = "INSERT INTO images (image) VALUES ('$imageContent')";
    if ($conn->query($sql) === TRUE) {
        echo "Image uploaded successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "No image uploaded.";
}

$conn->close();
?>
