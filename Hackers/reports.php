<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xml_file'])) {
    // Get the uploaded XML file
    $uploadedFile = $_FILES['xml_file'];

    // Check if it's a valid XML file
    if ($uploadedFile['error'] === UPLOAD_ERR_OK &&
        strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION)) === 'xml') {
        // Read the contents of the uploaded XML file
        $xmlData = file_get_contents($uploadedFile['tmp_name']);

        // Disable external entity loading
        libxml_disable_entity_loader(false);

        // Load the XML data
        $doc = new DOMDocument();
        $doc->loadXML($xmlData, LIBXML_NOENT | LIBXML_DTDLOAD);

        // Extract data from the XML
        $data = $doc->getElementsByTagName('data')->item(0)->nodeValue;

        // Display the extracted data
        echo "Data from XML: $data";
    } else {
        echo "Invalid XML file uploaded.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #444;
            text-align: center;
            padding: 20px;
        }

        p {
            font-size: 18px;
            text-align: center;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            max-width: 400px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

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
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <h1>Admin Reports</h1>
    <p>Upload the weekly insights report of the application</p>
    <form method="post" enctype="multipart/form-data">
        <label for="xml_file">Upload XML File:</label>
        <input type="file" name="xml_file" accept=".xml">
        <input type="submit" value="Upload Report">
    </form>
    <p>Download Example XML File: <a href="example.xml" download>xmlEx.xml</a></p>
</body>
</html>

