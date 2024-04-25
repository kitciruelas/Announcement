<?php
// Define the database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "database1";

// Connect to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Process form submission to add new announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Check if the title and content are set and not empty
    if (isset($_POST['announcement-title']) && isset($_POST['announcement-content'])) {
        $title = $_POST['announcement-title'];
        $content = $_POST['announcement-content'];

        // Insert the new announcement into the database with the current date
        $sql = "INSERT INTO announce (title, content, date_published) VALUES ('$title', '$content', NOW())";
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('New announcement added successfully');</script>";
            // Redirect to prevent resubmission
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Check if the update form is submitted
if(isset($_POST['update'])) {
    // Get the updated values from the form
    $announcement_id = $_POST['announcement-id'];
    $updated_title = $_POST['announcement-title'];
    $updated_content = $_POST['announcement-content'];

    // Update the announcement in the database
    $sql = "UPDATE announce SET title = '$updated_title', content = '$updated_content' WHERE announcementId = $announcement_id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Announcement updated successfully');</script>";
        // Redirect to the same page to refresh the announcements
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error updating announcement: " . mysqli_error($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
  //ID DELETE
    $announcementId = $_POST['announcement-id'];

    //DELETE query
    $sql = "DELETE FROM announce WHERE announcementId";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Announcement deleted successfully');</script>";
        // Redirect o iba pang action pagkatapos ng pag-delete
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error deleting announcement: " . mysqli_error($conn);
    }
}

// Display announcements from the database
$sql = "SELECT * FROM announce";
$result = mysqli_query($conn, $sql);

$announcements = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $announcements[] = $row;
    }
}

// Close the database connection
mysqli_close($conn);
?>
