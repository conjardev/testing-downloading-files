<?php
    $name = htmlspecialchars($_POST["name"], ENT_QUOTES);
    $updtFreq = htmlspecialchars($_POST["updatefrequency"], ENT_QUOTES);
    $updtHour = htmlspecialchars($_POST["updatehour"], ENT_QUOTES);
    $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
    $newPassword = hash("sha512", (htmlspecialchars($_POST["password"], ENT_QUOTES).time().rand(100,1000)));

    if (!$name || !$updtFreq || !$updtHour || !$username || !$newPassword) {
        die("Not enough value provided");
    }

    echo $name."<br>";
    echo $updtFreq."<br>";
    echo $updtHour."<br>";
    echo $username."<br>";
    echo $newPassword."<br>";
    echo "<br><br>";

    $servername = "localhost:3306";
    $username = "username";
    $password = "password";

    // Create connection
    $conn = new mysqli($servername, $username, $password);
    // Check connection
    if ($conn->connect_error) {
    //die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Connected to db<br>";
    }

    $sql = "SET PASSWORD = 'INPUT';";
    //mysqli_query($conn, "SET PASSWORD = '".$newPassword."'");
    $result = $conn->query($sql);
    print_r("Attempted to update password with result ".$result."<br>");

    $sql = "RENAME USER 'username'@'localhost' TO 'sus'@'localhost';";
    //mysqli_query($conn, "SET PASSWORD = '".$newPassword."'");
    $result = $conn->query($sql);
    print_r("Attempted to update username with result ".$result."<br>");
    
    echo "<br>Starting pass write";
    require_once('query_passwords.php');
    createPassword("controller", $newPassword);


    echo "<br>Uploaded";
    
    

    $conn-> close();
?>
