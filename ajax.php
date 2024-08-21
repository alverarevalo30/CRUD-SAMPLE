<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "countries";

if (isset($_POST['key'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //display data to fields
    if ($_POST['key'] == 'getRowData') {
        $editRowID = $conn->real_escape_string($_POST['editRowID']);

        $sql = $conn->query("SELECT name, shortDesc, longDesc FROM country WHERE id = '$editRowID'");
        $data = $sql->fetch_array();
        $jsonArray = array(
            'name' => $data['name'],
            'shortDesc' => $data['shortDesc'],
            'longDesc' => $data['longDesc'],
        );

        exit(json_encode($jsonArray));
    }

    // Show Data
    /* if ($_POST['key'] == 'getData') {
        $start = $conn->real_escape_string($_POST['start']);
        $limit = $conn->real_escape_string($_POST['limit']);

        $sql = $conn->query("SELECT id, name FROM country LIMIT $start, $limit");
        if ($sql->num_rows > 0) {
            $response = "";
            while ($data = $sql->fetch_array()) {
                $response .= '
                    <tr>   
                        <td>
                            '. $data["id"].' 
                        </td>
                        <td id = "country'. $data["id"].'">
                            '. $data["name"].' 
                        </td>
                        <td>
                            <input type = "button" onclick = "viewOrEdit('.$data["id"].', \'edit\')" value = "Edit" class = "btn btn-primary">
                            <input type = "button" onclick = "viewOrEdit('.$data["id"].', \'view\')" value = "View" class = "btn btn-secondary">
                            <input type = "button" onclick = "deleteRow('.$data["id"].')" value = "Delete" class = "btn btn-danger">
                        </td>
                    </tr>
                ';
            }
            exit($response);
        } else {
            exit('reachedMax');
        }
    } */

    if ($_POST['key'] == 'updateRow') {
        $name = $conn->real_escape_string($_POST['name']);
        $shortDesc = $conn->real_escape_string($_POST['shortDesc']);
        $longDesc = $conn->real_escape_string($_POST['longDesc']);
        $rowID = $conn->real_escape_string($_POST['rowID']);

        $conn->query("UPDATE country SET name = '$name', shortDesc = '$shortDesc', longDesc = '$longDesc' WHERE id = '$rowID'");
        exit('success');
    }

    if ($_POST['key'] == 'add') {
        $name = $conn->real_escape_string($_POST['name']);
        $shortDesc = $conn->real_escape_string($_POST['shortDesc']);
        $longDesc = $conn->real_escape_string($_POST['longDesc']);

        $sql = $conn->query("SELECT id FROM country WHERE name = '$name'");
        if ($sql->num_rows > 0) {
            exit("Country With This Name Already Exists!");
        } else {
            $stmt = $conn->prepare("INSERT INTO country (name, shortDesc, longDesc) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $shortDesc, $longDesc);
            $stmt->execute();
            $stmt->close();
            exit('success');
        }
    }

    if ($_POST['key'] == 'deleteRow') {
        $editRowID = $conn->real_escape_string($_POST['editRowID']);
        $conn->query("DELETE FROM country WHERE id = '$editRowID'");
        exit('deleted');
    }

    $conn->close();
}