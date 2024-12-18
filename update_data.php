<?php
require_once "db.php";

$id = $_POST['id'];
$tenhanghoa = $_POST['tenhanghoa'];
$nhacungcap = $_POST['nhacungcap'];
$hinhanh = $_FILES['hinhanh'];

if ($hinhanh['name']) {
    $targetFile = "uploads/" . basename($hinhanh['name']);
    move_uploaded_file($hinhanh['tmp_name'], $targetFile);
    $query = "UPDATE hanghoa SET tenhanghoa='$tenhanghoa', nhacungcap='$nhacungcap', hinhanh='$hinhanh[name]' WHERE id=$id";
} else {
    $query = "UPDATE hanghoa SET tenhanghoa='$tenhanghoa', nhacungcap='$nhacungcap' WHERE id=$id";
}

$conn->query($query);
?>