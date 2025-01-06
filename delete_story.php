<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); 
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_samesite', 'Strict');

session_start();

include("config/db_connect");

if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $storyId = $_GET['id'];
} else {
    header("location: heheh.php");
    exit;
}

$loggedInUserId = $_SESSION['user_id'];
$sql = "SELECT username FROM stories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $storyId);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $row = $result->fetch_assoc();
    $author = $row['username'];
    $stmt->free_result();
} else {
    header("location: offf.php");
    exit;
}

$sqlDelete = "DELETE FROM stories WHERE id = ?";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->bind_param("i", $storyId);
if ($stmtDelete->execute()) {
    header("location: index.php");
} else {
    echo 'Erreur de suppression: ' . $stmtDelete->error;
}
?>