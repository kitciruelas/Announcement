<?php
// Define the database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password is set
$dbname = "database1";

// Connect to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process form submission to add new entry
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to store any errors
    $errors = [];

    // Check if all required fields are set and not empty
    $requiredFields = ['section', 'title', 'scheduleType', 'description', 'location', 'selectedDays', 'startTime', 'endTime', 'startMonth', 'endMonth'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst($field) . " field is required.";
        }
    }

    // If there are no errors, proceed with inserting the entry into the database
    if (empty($errors)) {
        // Retrieve form data
        $section = $_POST['section'];
        $title = $_POST['title'];
        $scheduleType = $_POST['scheduleType'];
        $description = $_POST['description'];
        $location = $_POST['location'];
        $selectedDays = implode(",", $_POST['selectedDays']); // Convert array to comma-separated string
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $startMonth = $_POST['startMonth'];
        $endMonth = $_POST['endMonth'];

        // Insert the new entry into the database
        $sql = "INSERT INTO schedule (section, title, scheduleType, description, location, selectedDays, startTime, endTime, startMonth, endMonth) VALUES ('$section', '$title', '$scheduleType', '$description', '$location', '$selectedDays', '$startTime', '$endTime', '$startMonth', '$endMonth')";
        if (mysqli_query($conn, $sql)) {
            echo "New entry added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        // If there are errors, display them
        foreach ($errors as $error) {
            echo '<script>alert("' . $error . '");</script>';
        }
    }
    
}

// Close the database connection
mysqli_close($conn);
?>
