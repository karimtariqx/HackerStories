<?php

$login = 0;
$invalid  = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include ("config/db_connect");
    $username = $_POST["username"];
    $password = $_POST["password"];



    


    $sql = "SELECT * FROM `users` WHERE username='$username' AND password='$password'"; 


    $result = mysqli_query($conn, $sql);

    if($result)
    {
        $num = mysqli_num_rows($result);
        if($num != 0)
        {
            echo "LOGIN SUCCESSFULLY";
            session_start();   
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION["user_id"] = $user_data["user_id"]; 
            $_SESSION["username"] = $username;
            $_SESSION["password"] = $password;

            $page = 'en';
  
            header("location:index.php?page=" . $page);
           
        }
        else{
            echo "Invalid credentials";
        }
        
    }

}

?>




<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh"content="800">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>first Project </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="refresh"content="800">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>first Project </title>
    
  </head>
  <body>


 


  <h1 class="text-center mt-3">Login</h1>

  <div class="container mt-5">

  <form action="login.php" method="POST">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Username</label>
    <input type="text" class="form-control" placeholder="username" name="username">
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control" placeholder="password" name="password">
  </div>
  
  <button type="submit" class="btn btn-primary w-100">LOGIN</button>
</form>

<a href="sign.php">Register?</a>
  </div>
    










    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>