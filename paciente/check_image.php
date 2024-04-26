<?php
$imagePath = $_POST['image_path'];

$imageDirectory = '../assets/';

$imageFullPath = $imageDirectory . $imagePath;

$exists = file_exists($imageFullPath);

header('Content-Type: application/json');
echo json_encode(['exists' => $exists]);
