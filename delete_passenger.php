<?php
// delete_passenger.php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}
$id = (int)$_GET['id'];
$stmt = $conn->prepare("DELETE FROM passengers WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();
header("Location: dashboard.php");
exit();
?>
