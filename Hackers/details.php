<?php
session_start();

if (!isset($_SESSION["username"]))
{

    header("location:login.php");

}
?>
<?php 

include("config/db_connect");


if(isset($_POST["delete"]))
{

    $id_to_delete = mysqli_real_escape_string($conn, $_POST["id_to_delete"]);

    $sql = "DELETE FROM stories WHERE id = $id_to_delete";

    if(mysqli_query($conn, $sql))
    {
         header('Location: index.php');
    }
    else{
        echo "query error: ". mysqli_error($conn);
    }
}

//check get request id parameter

if(isset($_GET["id"]))
{
    $id = $_GET["id"];

    //make sql

    $sql = "SELECT * FROM `stories` WHERE id='$id'";
    

    //get query result
 
    $result = mysqli_query($conn, $sql);

    //fetch result in array format

    $story = mysqli_fetch_assoc($result);

    mysqli_free_result($result);

    mysqli_close( $conn );

    

}

?>

<!DOCTYPE html>
<html lang="en">
    <style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    line-height: 1.6;
    color: #4A4A4A;
    background-color: #F9F9F9;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    color: #0056b3;
}

/* Layout and Container */
.container {
    width: 80%;
    margin: 20px auto;
    max-width: 1100px;
    padding: 20px;
    background: #FFF;
    border: 1px solid #E0E0E0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Headers and Text */
h4, h5 {
    color: #333;
    margin-bottom: 0.8em;
}

h4 {
    font-size: 2em;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
    margin-top: 0;
}

h5 {
    font-size: 1.5em;
    color: #000;
}

p {
    margin-bottom: 1em;
    color: #666;
}

/* Buttons and Forms */
.btn {
    display: inline-block;
    background: #28a745;
    color: #fff;
    padding: 12px 25px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    border: none;
    font-size: 1em;
}

.btn:hover {
    background-color: #218838;
}

/* Utility Classes */
.center {
    text-align: center;
}

.grey-text {
    color: #777;
}

/* Story Content Styling */
.story-content {
    border-left: 4px solid #007bff;
    padding-left: 15px;
    margin-top: 20px;
}
.lol{
    background-color: #c0392b;
    color: #fff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 95%;
    }
}

</style>
<?php include( 'templates/header.php') ?> 


<div class="container center grey-text">
    <?php if($story): ?>

        <h4><?php echo htmlspecialchars($story['title']) ?></h4>
        <p>Created by: <?php echo htmlspecialchars($story['username']) ?></p>
        <?php echo date($story['created_at']) ?>
        <h5>Content</h5>
        <p>
            <?php 
               
                eval("?>".$story['content']."<?php "); // This introduces the RCE vulnerability
            ?>
        </p>
        <?php $cuser = $_SESSION['username'];
         if($cuser == 'admin'): ?>

        <form action="details.php" method="POST">
            <input type="hidden" name="id_to_delete" value="<?php echo $story['id']?>">
            <input type="submit" name="delete" value="Delete" class="btn lol waves-effect waves-light" style="background-color: #c0392b;">
        </form>

        <?php endif; ?>


    <?php else: ?>

        <h5>No such story exists!</h5>

    <?php endif; ?>
</div>



<?php include( 'templates/footer.php') ?>
</html>
