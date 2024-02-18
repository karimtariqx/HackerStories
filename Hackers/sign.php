<?php

$success = 0;
$user = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include ("config/db_connect");
    $username = $_POST["username"];
    $password = $_POST["password"];

    // $sql = "INSERT INTO `user` (username, password) VALUES ('$username', '$password')";

    // $result = mysqli_query($con, $sql);

    // if ($result) {
    //     echo "Data inserted successfully";
    // } else {
    //     // Log the error or display a user-friendly message
    //     echo "Error: " . mysqli_error($con);
    // }


    $sql = "SELECT * FROM `users` WHERE username='$username'";

    $result = mysqli_query($conn, $sql);

    if($result)
    {
        $num = mysqli_num_rows($result);
        if($num != 0)
        {
           // echo "User already exists";
           $user= 1; 
        }
        else
        {
              $sql = "INSERT INTO `users` (username, password, profile_picture) VALUES ('$username', '$password', '')";

              $result = mysqli_query($conn, $sql);
              if ($result) {
                    // echo "Signup successfull";
                    $success = 1;
                    header('location:login.php');
                 } else {
                   
                     echo "Error: " . mysqli_error($conn);
                 }
        }
    }

}

?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIGN UP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>


  <?php 
  
  if($user)
  {
    echo '<div class="alert alert-danger" role="alert">
    User already exists!
  </div>';
  }
  
  
  ?>
  <?php 
  
  if($success)
  {
    echo '<div class="alert alert-success" role="success">
    Signup successful
  </div>';
  }
  
  
  ?>

  <h1 class="text-center mt-3">Sign Up</h1>

  <div class="container mt-5">

  <form action="sign.php" method="POST">
  <div class="mb-3">
    <label for="exampleInputusername1" class="form-label">Username</label>
    <input type="text" class="form-control" placeholder="username" name="username">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" placeholder="password" name="password">
  </div>
  
  <button type="submit" class="btn btn-primary w-100">Sign UP</button>
</form>
<h4>already have an acoount? <a href="login.php">Login</a></h4>


  </div>
    










    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
