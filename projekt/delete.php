<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    die("Access denied");
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    header('Location: index.php'); 
}
?>
