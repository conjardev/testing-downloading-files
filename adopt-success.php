<?php
$servername = "localhost:3306";

$dbname = "robots";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$ip = htmlspecialchars($_GET["adoptedip"], ENT_QUOTES);

$sql = "SELECT * FROM `Devices` WHERE `ip` =  '".$ip."' LIMIT 1";
$result = $conn->query($sql);


$data = "";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $data = $row;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="box">
        <img id="thumbnail" src="https://i5.walmartimages.com/asr/5abac3ae-a02e-4c9a-b82a-a20ea3f90ff0_1.086c1a5514f36bfa954d7e5769df7306.jpeg">
        <div id="vline"></div>
        <h1><?php echo $data["Name"] ?></h1>
    </div>

    <div class="box">
        <div class="content">
            <h2>Setup is now complete!</h2>
            <button onclick="window.location.href = './'">Go to control center</button>
        </div>
    </div>

    <div class="box" id="line-top">
        <h3><?php echo $ip;?></h3>
    </div>

    <style>
        body {
            color: #323232;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;

            height: 100vh;
            overflow: hidden;
            margin: 0;

            display: grid;
            grid-template-rows: 30% 50% 20%;
            grid-template-columns: 100vw;
            justify-content: center;
            justify-items: center;
        }

        #vline {
            height: 100px;
            width: 1px;
            margin: 10px;

            background-color: #e4e4e4;
        }

        #line-top {
            border-top: 1px solid #e4e4e4;
        }

        #thumbnail {
            aspect-ratio: 1/1;
            height: 100px;
            object-fit: contain;
        }

        .box {
            display: flex;
            align-items: center;
            width: 100%;

            flex-direction: row;
            justify-content: center;
        }

        .box .content {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        button {
            height: 50px;
            border: solid #d7d7d7 1px;
            padding: 5px;
            font-weight: bolder;
            cursor: pointer;
        }

        h1 {
            font-weight: bolder;
        }

        h3 {
            color: #747474;
            font-weight: lighter;
            font-size: 0.9em;
        }
    </style>
</body>
</html>
