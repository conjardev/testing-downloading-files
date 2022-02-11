<?php
    $name = htmlspecialchars($_POST["name"], ENT_QUOTES);
    $updtFreq = htmlspecialchars($_POST["updatefrequency"], ENT_QUOTES);
    $updtHour = htmlspecialchars($_POST["updatehour"], ENT_QUOTES);
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
    $password = hash("sha512", (htmlspecialchars($_POST["password"], ENT_QUOTES).time().rand(100,1000)));

    if (!$name || !$updtFreq || !$updtHour || !$username || !$password) {
        die("Not enough value provided");
    }

    echo $name."<br>";
    echo $updtFreq."<br>";
    echo $updtHour."<br>";
    echo $username."<br>";
    echo $password."<br>";
    echo "<br><br>";

    $servername = "localhost:3306";
    $username = "username";
    $password = "password";

    // Create connection
    $conn = new mysqli($servername, $username, $password);
    // Check connection
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Connected to db<br>";
    }

    //$sql = "SET PASSWORD = 'INPUT';";
    mysqli_query($conn, "SET PASSWORD = '".$password."'");
    $result = $conn->query($sql);
    print_r("Called with result ".$result);
    echo "<br><br>";
    print_r("Called with result ".$result);

                
    $conn-> close();
?>
