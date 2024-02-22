<?php
session_start();

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

include "classes/Database.php";

date_default_timezone_set("Africa/Lagos");

$database = new Database();
$database = $database->getConnection();

$login_session_duration = 2800;
$current_time = time();

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}
$sql = "SELECT * FROM Users where ID = :id";
$stmt = $database->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_OBJ);
if ($stmt->rowCount() == 0) {
    session_destroy();
    header("Location: auth/login.html");
}


if (isset($_SESSION['last_acted_on'])) {
    if (($current_time - $_SESSION['last_acted_on'] > $login_session_duration)) {
        session_destroy();
        header("Location: auth/login.html");
    }
} else {
    header("Location: auth/login.html");
}

function isValidId($id)
{
    $database = new Database();
    $database = $database->getConnection();

    $sql = "SELECT * FROM Memo where ID = :id";
    $query = $database->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() == 0) {
        session_destroy();
        header("Location: auth/login.html");
    } else {
        return true;
    }
}

function formatID($id)
{
    $database = new Database();
    $database = $database->getConnection();

    $sql = "SELECT * FROM MemoFormat where ID = :id";
    $query = $database->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() == 0) {
        session_destroy();
        header("Location: auth/login.html");
    } else {
        return true;
    }
}
