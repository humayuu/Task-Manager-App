<?php

$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
$maxFilesize = 2 * 2 * 1024; //2MB
$uploadDirectory = __DIR__ . '/uploads/';
if (!is_dir($uploadDirectory)) mkdir($uploadDirectory, 0755, true);

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
    $tmpFile = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    if ($size > $maxFilesize) {
        return false;
    } elseif (!in_array($ext, $allowedExtensions)) {
        return false;
    }

    $filename = uniqid('image_') . time() . '.' . $ext;

    if (!move_uploaded_file($tmpFile, $uploadDirectory . $filename)) {
        return false;
    } else {
        return true;
    }
}