<?php
// Database connection
$servername = "localhost";
$username = "root";  // Change if necessary
$password = "";      // Change if necessary
$database = "intrasix"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get search query
if (isset($_GET['name'])) {
    $name = $conn->real_escape_string($_GET['name']);
    
    // Search for user in the database
    $sql = "SELECT id, name, email, dob, gender, town, profile_pic FROM users WHERE name = '$name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode([
            "status" => "success",
            "id" => $user["id"],
            "name" => $user["name"],
            "email" => $user["email"],
            "dob" => $user["dob"],
            "gender" => $user["gender"],
            "town" => $user["town"],
            "profile_pic" => $user["profile_pic"]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "User does not appear here."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Please provide a name to search."]);
}

// Close the connection
$conn->close();
?>