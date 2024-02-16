<?php
session_start();

include("config/db_connect");

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if the user is not logged in
    header("location: login.php");
    exit;
}

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $storyId = $_GET['id'];
} else {
  
    header("location: heheh.php");
    exit;
}

// Check if the logged-in user is the author of the story with the provided ID
$loggedInUserId = $_SESSION['user_id'];
$sql = "SELECT username FROM stories WHERE id = '$storyId'";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $author = $row['username'];
    mysqli_free_result($result);

} else {
    // Unable to fetch the story's author
    header("location: offf.php");
    exit;
}

// If the logged-in user is the author
$sqlDelete = "DELETE FROM stories WHERE id = '$storyId'";

if (mysqli_query($conn, $sqlDelete)) {
    // Story deleted successfully
    header("location: profile.php?uid=$loggedInUserId");
    exit;
} else {
    // Error occurred while deleting the story
    header("location: lol.php");
    exit;
}

?>
