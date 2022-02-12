<?php
    require('query_passwords.php');
    require('controllerInfo.php');

    $name = htmlspecialchars($_POST["name"], ENT_QUOTES);
    $updtFreq = htmlspecialchars($_POST["updatefrequency"], ENT_QUOTES);
    $updtHour = htmlspecialchars($_POST["updatehour"], ENT_QUOTES);
    $newUsername = htmlspecialchars($_POST["username"], ENT_QUOTES);
    if (!getPass("controller")) {
        // No new pass has been set
        $newPassword = hash("sha512", (htmlspecialchars($_POST["password"], ENT_QUOTES).time().rand(100,1000)));
    } else {
        // A new pass has been already defined
        $newPassword = getPass("controller");
    };
    

    if (!$name || !$updtFreq || !$updtHour || !$newUsername || !$newPassword) {
        die("Not enough value provided<br>Either the program is broken (start an issue on github), or you're not supposed to be here! (naughty naughty)");
    }

    echo $name."<br>";
    echo $updtFreq."<br>";
    echo $updtHour."<br>";
    echo $newUsername."<br>";
    echo $newPassword."<br>";
    echo "<br><br>";

    $servername = "localhost:3306";
    if (!getPass("controller")) {
        // No password has been set, connect with default info
        $username = "username";
        $password = "password";
    } else {
        // A password is in the passwords file, we can assume this means
        // that it has been set already
        $username = $newUsername;
        $password = getPass("controller");
    }

    // Create connection
    $conn = new mysqli($servername, $username, $password);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Connected to db<br>";
    }

    if (!getPass("controller")) {
        // The new password has not yet been created, this means we can change to it

        $sql = "SET PASSWORD = '".$newPassword."';";
        $result = $conn->query($sql);
        print_r("Attempted to update password with result ".$result."<br>");
        
        $sql = "RENAME USER 'username'@'localhost' TO '".$newUsername."'@'localhost';";
        $result = $conn->query($sql);
        print_r("Attempted to update username with result ".$result."<br>");
        
        
        echo "<br>Starting pass write";
        createPassword("controller", $newPassword);
        echo "<br>Uploaded";

        updateControllerInfo($name, $updtFreq, $updtHour, $newUsername);
        header("Location: adopt-success.php?adoptedip=controller");
    } else {
        echo "Info already set<br>";
        $conn-> close();
        $conn = new mysqli($servername, $newUsername, $newPassword);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            echo "Connected to db under username ".$newUsername;
        }
    }
    

    $conn-> close();
?>