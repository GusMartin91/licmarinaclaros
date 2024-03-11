<?php

session_start();

$loggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

echo json_encode([
    'loggedIn' => $loggedIn,
]);
