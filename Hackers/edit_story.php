<?php
session_start();

include("config/db_connect");

// Redirect if not logged in
if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit;
}



// Check if story ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Story ID is required";
    exit;
}

$story_id = $_GET['id'];
$errors = array('title' => '', 'content' => '');

// Fetch the story from the database
$sql = "SELECT * FROM stories WHERE id = $story_id";
$result = mysqli_query($conn, $sql);
$story = mysqli_fetch_assoc($result);

if (!$story) {
    echo "No story found with that ID";
    exit;
}

// Process form submission
if (isset($_POST["update"])) {
    // Validate inputs
    if (empty($_POST['title'])) {
        $errors['title'] = 'Title is required';
    } else {
        $title = $_POST['title'];
        // if (!preg_match('/^[a-zA-Z\s]+$/', $title)) {
        //     $errors['title'] = 'Title must be letters and spaces only';
        // }
    }

    if (empty($_POST['content'])) {
        $errors['content'] = 'Content is required';
    } else {
        $content = $_POST['content'];
        // Adjust the regex as per your content validation rules
    }

    // Update the story if no errors
    if (!array_filter($errors)) {
        $title = mysqli_real_escape_string($conn, $title);
        $content = mysqli_real_escape_string($conn, $content);

        $sql = "UPDATE stories SET title = '$title', content = '$content' WHERE id = $story_id";

        if (mysqli_query($conn, $sql)) {
            header('Location: index.php');
        } else {
            echo 'Query error: ' . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Story</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.container {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.edit-form h2 {
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

.form-group input[type="text"],
.form-group textarea {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ddd;
    font-size: 16px;
}

.form-group textarea {
    height: 150px;
    resize: vertical;
}

.btn-submit {
    width: 100%;
    background-color: teal;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 18px;
}

.btn-submit:hover {
    background-color: #0056b3;
}

.error {
    color: #ff0000;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 90%;
    }
}

</style>
<body>
    <?php include('templates/header.php');?>

    <div class="container">
        <div class="edit-form">
            <h2>Edit Story</h2>
            <form action="edit_story.php?id=<?php echo $story_id; ?>" method="POST">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($story['title']); ?>">
                    <span class="error"><?php echo $errors['title']; ?></span>
                </div>

                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content"><?php echo htmlspecialchars($story['content']); ?></textarea>
                    <span class="error"><?php echo $errors['content']; ?></span>
                </div>

                <button type="submit" name="update" class="btn-submit  waves-effect waves-light">Update Story</button>
            </form>
        </div>
    </div>

    <?php include('templates/footer.php');?>
</body>
</html>
