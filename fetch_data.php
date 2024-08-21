<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "countries";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name FROM country";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '
            <tr>
                <td>' . $row["id"] . '</td>
                <td id="country' . $row["id"] . '">' . $row["name"] . '</td>
                <td>
                    <input type="button" onclick="viewOrEdit(' . $row["id"] . ', \'edit\')" value="Edit" class="btn btn-primary">
                    <input type="button" onclick="viewOrEdit(' . $row["id"] . ', \'view\')" value="View" class="btn btn-secondary">
                    <input type="button" onclick="deleteRow(' . $row["id"] . ')" value="Delete" class="btn btn-danger">
                </td>
            </tr>';
    }
} else {
    echo "0 results";
}
$conn->close();

