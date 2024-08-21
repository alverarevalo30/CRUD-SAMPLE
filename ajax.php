<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_POST['key'])) {
    $conn = new mysqli('localhost', 'root', 'password', 'countries');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $conn->real_escape_string($_POST['name']);
    $shortDesc = $conn->real_escape_string($_POST['shortDesc']);
    $longDesc = $conn->real_escape_string($_POST['longDesc']);

    // Add Data
    if ($_POST['key'] == 'add') {
        $sql = $conn->query("SELECT id FROM country WHERE name = '$name'");
        if ($sql->num_rows > 0) {
            exit("Country With This Name Already Exists!");
        } else {
            $stmt = $conn->prepare("INSERT INTO country (name, shortDesc, longDesc) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $shortDesc, $longDesc);
            $stmt->execute();
            $stmt->close();
            exit("Country Has Been Inserted!");
        }
    }

    $conn->close();
}
