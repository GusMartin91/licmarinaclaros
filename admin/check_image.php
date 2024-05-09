<?php
$imagePath = $_POST['image_path'];

$exists = file_exists($imagePath);

header('Content-Type: application/json');
echo json_encode(['exists' => $exists]);
