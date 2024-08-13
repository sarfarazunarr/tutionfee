<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tutionfee";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get today's date
$today = date('Y-m-d');

include 'sendmail.php';

// Debug: Test database connection and query
echo "Connected successfully. Today's date: $today\n";

// Query to find users whose active_till is today or in the past
$sql = "SELECT * FROM users WHERE DATE(active_till) <= '$today' AND isActive = 1";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userId = $row['id'];
        $email = $row['email'];
        $name = $row['username'];
        $rollNumber = $row['rollNumber'];
        $activeTill = $row['active_till'];

        echo "Processing user: $name ($email)\n";

        // Check if active_till is today (considering only the date part)
        if (substr($activeTill, 0, 10) == $today) {
            echo "Sending expiration warning email to $email\n";
            sendmail($email, $name, 'Your account will expire soon. Please renew your subscription.', "Your account is about to expire");
        }

        // If active_till is less than today, deactivate the user
        if (substr($activeTill, 0, 10) < $today) {
            $updateSql = "UPDATE users SET isActive = 0 WHERE id = $userId";
            if ($conn->query($updateSql) === true) {
                echo "User $name deactivated. Sending deactivation email to $email\n";
                sendmail($email, $name, 'Your account has been deactivated', "Account Deactivated");
            } else {
                echo "Error updating user $name: " . $conn->error . "\n";
            }
        }
    }
} else {
    echo "No users to process.\n";
}

$conn->close();

?>
