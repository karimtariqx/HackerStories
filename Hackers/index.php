<?php

include ("config/db_connect");
// write query for all stories

$sql = 'SELECT title, content, id FROM stories ORDER BY created_at';

// make query and get result

$result = mysqli_query( $conn, $sql );

//fetch the resulting rows as an array 

$stories = mysqli_fetch_all( $result , MYSQLI_ASSOC );


mysqli_free_result( $result );

//close the connection

mysqli_close( $conn );



function basicSanitization($input) {
    // Sanitize against specific characters
    $sanitized = str_replace(['<', '>', '"', "'"], '', $input);
    return $sanitized;
}



?>
<?php
session_start();

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Check if $page is not empty and is a valid value
    if (!empty($page)) {
        
        include($page); 
    }
}



if (!isset($_SESSION["username"]))
{

    header("location:login.php");

}

?>
<!DOCTYPE html>
<html lang="en">

<?php include( 'templates/header.php') ?> 

<h2 class="center  " style="color:#333;" >Welcome <?php echo $_SESSION['username']; ?></h4>
<div class="container">
    <div class="row">
        <form action="search.php" method="GET">
            <div class="input-field col s12">
                <input type="text" name="search" id="search" placeholder="Search by Title">
                <button class="btn waves-effect waves-light" type="submit">Search</button>
            </div>
        </form>
    </div>
</div>


<div class="container">

<div class="row">

<?php foreach($stories as $story): ?>

<div class="col s6 md3">
    <div class="card z-depth-0">
        <img src="img\book-4986.png" class="story" alt="">
        <div class="card-content center">
            <?php $sanitizedComment = basicSanitization($story['title']);
            $decodedSanitizedComment = urldecode($sanitizedComment);
            ?>
            <h6><?php echo $decodedSanitizedComment ?></h6>
            <ul>
                <?php foreach(explode(',',$story['content']) as $ing): ?>

                    <li><?php echo htmlspecialchars($ing) ?></li>

                <?php endforeach; ?>
        </ul>
        </div>
        <div class="card-action right-align">
        <button class="btn waves-effect waves-light"> <a  href="details.php?id=<?php echo $story['id']  ?>" class="" style="color: #fff;">MORE INFO</a></button>
        </div>
    </div>
</div>

    <?php endforeach;?>
</div>

</div>

<?php include( 'templates/footer.php') ?>







    
</body>
</html>
