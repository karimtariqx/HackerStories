<?php

session_start();







// Handle the URL input and fetch data if 'url' parameter is set
$dataFetched = "";
if(isset($_GET['url'])) {
    $url = $_GET['url'];

    // Fetching data from the provided URL without validation, leading to SSRF vulnerability
    $dataFetched = file_get_contents($url);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSRF Vulnerability Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
            color: white;
            padding: 14px 16px;
        }
        .navbar a {
            float: left;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .main {
            margin: 15px;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            opacity: 0.9;
        }
        .error {
            color: red;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="index.php">Hacker Stories</a>
        
    </div>

    <div class="main">
        <h2>Get Idea</h2>
        <form action="ssrf_vulnerable.php" method="GET">
            <label for="url">Enter URL to fetch data from:</label>
            <input type="text" id="url" name="url" placeholder="http://example.com">
            <button type="submit">Fetch Data</button>
        </form>
        <?php
        if(isset($_GET['url'])) {
            $url = $_GET['url'];
            
            $dataFetched = @file_get_contents($url);
            if($dataFetched !== false) {
                echo '<div><strong>Fetched Data:</strong></div>';
                echo '<div class="error">'.htmlspecialchars($dataFetched).'</div>';
            } else {
                echo '<div class="error">Data could not be fetched from the specified URL.</div>';
            }
        }
        ?>
    </div>
</body>
</html>

