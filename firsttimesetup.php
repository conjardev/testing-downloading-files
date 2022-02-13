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

    function writeDB($servername, $newUsername, $newPassword, $dbname) {
        $conn = new mysqli($servername, $newUsername, $newPassword);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
        echo "Connected to db under username ".$newUsername;
            // Set up DB
            $sql = "CREATE DATABASE IF NOT EXISTS ".$dbname.";";
            $result = $conn->query($sql);
            if ($conn->query($sql) === TRUE) {
                // The db was sucessfully created, now connect to that
                echo "<br>Database created successfully";
                $conn-> close();
                $conn = new mysqli($servername, $newUsername, $newPassword, $dbname);
                if ($conn->connect_error) {
                    // Could not connect
                    die("Connection failed: " . $conn->connect_error);
                }
                // Code execute here is if we connected sucessfully

                // Create "devices" table
                $sql = "CREATE TABLE IF NOT EXISTS `Devices` (
                    `UUID` int NOT NULL AUTO_INCREMENT,
                    `ip` text NOT NULL,
                    `Name` text NOT NULL,
                    `Deployment` text NOT NULL,
                    `Recording` text NOT NULL,
                    `Type` text NOT NULL,
                    PRIMARY KEY (`UUID`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4;
                ";
                if ($conn->query($sql) === TRUE) {
                    echo "<br>Table Robots created successfully";
                } else {
                    echo "<br>Error creating table: " . $conn->error;
                    echo "<br>SQL = ".$sql;
                }

                // Create "wizards" table
                $sql = "CREATE TABLE IF NOT EXISTS `Wizards` (
                    `UUID` int NOT NULL AUTO_INCREMENT,
                    `Device` text NOT NULL,
                    `IndexName` text NOT NULL,
                    PRIMARY KEY (`UUID`)
                   ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
                ";

                if ($conn->query($sql) === TRUE) {
                    echo "<br>Table Wizards created successfully";
                    // Populate wizards
                    $sql = "
                    INSERT INTO `Wizards` (`UUID`, `Device`, `IndexName`)
                    SELECT * FROM (SELECT NULL, 'stationary', 'stationary-setup') AS tmp
                    WHERE NOT EXISTS (
                        SELECT `Device` FROM `Wizards` WHERE `Device` = 'stationary'
                    ) LIMIT 1;";
                    
                    if ($conn->query($sql) === TRUE) {
                        echo "New record created successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                        echo "<br>SQL = ".$sql;
                    }

                } else {
                    echo "<br>Error creating table: " . $conn->error;
                    echo "<br>SQL = ".$sql;
                }

            } else {
                echo "<br>Error creating database: " . $conn->error;
                echo "<br>SQL = ".$sql;
            }
    }
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
        $dbname = 'robots';
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
        $conn-> close();
        writeDB($servername, $newUsername, $newPassword, $dbname);
        // header("Location: adopt-success.php?adoptedip=controller");
    } else {
        echo "Info already set<br>";
        $conn-> close();
        writeDB($servername, $newUsername, $newPassword, $dbname);
    }
    

    $conn-> close();
?>