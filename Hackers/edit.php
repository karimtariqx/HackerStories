<?php
session_start();

include("config/db_connect");
$successMessage = "";
$errorMessage = "";

function generateCSRFToken() {
    return bin2hex(random_bytes(32)); // You can adjust the token length as needed
}

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to a different page if the user is not logged in
    header("location:some_error_page.php");
    exit;
}

$loggedInUserId = $_SESSION['user_id'];

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $profileUserId = $_GET['id'];
} else {
    // 'id' parameter is not provided, you can handle this case as needed (e.g., redirect to an error page)
    header("location:some_error_page.php");
    exit;
}

// Security Check: Ensure that the logged-in user can only edit their own profile
if ($loggedInUserId != $profileUserId) {
    // Unauthorized access, redirect to an error page or display a message
    header("location:some_error_page.php");
    exit;
}

// Generate and store a CSRF token in the user's session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCSRFToken();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']))  {
    // Handle username change
    if (isset($_POST['new_username']) && !empty($_POST['new_username'])) {
        $newUsername = $_POST['new_username'];
        // Update the 'username' column in the 'users' table using prepared statements
        $updateUsernameSQL = "UPDATE users SET username = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $updateUsernameSQL);
        mysqli_stmt_bind_param($stmt, 'si', $newUsername, $loggedInUserId);
        if (mysqli_stmt_execute($stmt)) {
            $successMessage = "Username updated successfully.";
        } else {
            $errorMessage = "Error updating username.";
        }
    }

    // Handle password change
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $newPassword = $_POST['new_password'];
        // Update the 'password' column in the 'users' table using prepared statements
        $updatePasswordSQL = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $updatePasswordSQL);
        mysqli_stmt_bind_param($stmt, 'si', $newPassword, $loggedInUserId);
        if (mysqli_stmt_execute($stmt)) {
            $successMessage = "Password updated successfully.";
        } else {
            $errorMessage = "Error updating password.";
        }
    }
    

// Handle profile picture upload
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    // Define the upload directory relative to the root directory of your website
    $uploadDirectory = 'profile_pics/';

    // Generate a unique file name to prevent overwriting existing files
    // This uses the current Unix timestamp and appends it to the original file name
    $fileName = time() . '_' . basename($_FILES['profile_picture']['name']);
    
    // Specify the path to the temporary file and the new file path
    $tempFilePath = $_FILES['profile_picture']['tmp_name'];
    $newFilePath = $uploadDirectory . $fileName;

    // Move the uploaded file from the temporary directory to the target directory
    if (move_uploaded_file($tempFilePath, $newFilePath)) {
        // Update the 'profile_picture' column in the 'users' table with the new file path
        // Ensure you have an active database connection `$conn` and the `$loggedInUserId` is set correctly
        $updateProfilePictureSQL = "UPDATE users SET profile_picture = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $updateProfilePictureSQL);
        mysqli_stmt_bind_param($stmt, 'si', $fileName, $loggedInUserId); // Store only the file name, not the entire path
        mysqli_stmt_execute($stmt);
        $successMessage = "Profile picture updated successfully.";
    } else {
        // Handle error if the file wasn't moved successfully
        // This could be due to permissions, incorrect paths, or other server settings
        echo "There was an error uploading the file.";
    }
}

$_SESSION['csrf_token'] = generateCSRFToken();

}

$fileName = '';

// Fetch user details from the database
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $loggedInUserId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    $user = mysqli_fetch_assoc($result);
    if (!empty($user['profile_picture'])) {
        $fileName = $user['profile_picture'];
    }
    mysqli_free_result($result);
}
// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="800">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Add your CSS styles and JavaScript libraries here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h1 {
            color: #444;
        }

        img {
            max-width: 150px;
            height: auto;
            border-radius: 75px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #337ab7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Edit Profile</h1>

    <!-- Display the current profile picture -->
    <?php if($fileName):?>
    <?php $imagePath = 'profile_pics/' . $fileName; ?>
            <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Picture"><br>
            <?php endif;?>
    <!-- Profile Picture Upload Form -->
    <form action="edit.php?id=<?php echo htmlspecialchars($profileUserId); ?>" method="POST" enctype="multipart/form-data">
        <!-- Allow the user to upload a new profile picture -->
        <label for="profile_picture">Upload New Profile Picture:</label>
        <input type="file" name="profile_picture" accept="image/*"><br>

        <!-- Username Change -->
        <label for="new_username">Change Username:</label>
        <input type="text" name="new_username" placeholder="New Username"><br>

        <!-- Password Change -->
        <label for="new_password">Change Password:</label>
        <input type="password" name="new_password" placeholder="New Password"><br>

        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

        <input type="submit" value="Update Profile">
    </form>
    <?php if (!empty($successMessage)): ?>
    <div style="color: green;"><?php echo htmlspecialchars($successMessage); ?></div>
<?php endif; ?>

<?php if (!empty($errorMessage)): ?>
    <div style="color: red;"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>
    <!-- Add a link to go back to the profile page -->
    <a href="profile.php?uid=<?php echo htmlspecialchars($profileUserId); ?>">Back to Profile</a>
</body>
</html>
