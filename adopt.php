<?php
$servername = "localhost:3306";
$username = "webmaster";
$password = "oKbxmGhGxzDbRVrfAlUR";
$dbname = "robots";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$ip = htmlspecialchars($_POST["ip"], ENT_QUOTES);
echo "Attempting to adopt ";
echo $ip;
echo "<br>";

echo"<br><br>";
$sql = "SELECT * FROM `Devices` WHERE `ip` =  '".$ip."'";
$result = $conn->query($sql);


$isDataCollected = false;
$status = "0";
if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $status = "2";
    echo "Device already adopted <br> ".json_encode($row);
  }
} else {
    echo "Not yet adopted, calling<br>";

    $request = file_get_contents("http://".$ip."/adopt-info.php");
    $json = json_decode($request, true); 

    foreach ($json as $key => $value) {
        echo $key." = ".$value."<br>";
    }

    if ($json["verificationCode"] == "nonSecurePasswordToVerifyDevice") {
        $status = "1";
        echo "<br>Calling the onboard api to set the password";
        echo "Preparing a password for ".$ip;
        $key = hash("sha512", (hash("ripemd160", ($ip.time()*rand(100,1000)).time()).$ip)); // Combine a bunch of variables to create a key that cannot be recreated
        echo "<br>".$key."<br>";
        
        function getValueFromInfo($key, $ip) {
          $request = file_get_contents("http://".$ip."/_softwareinfo.yaml");
          $blank = (explode("\n",$request));
          
          foreach ($blank as $line) {
            $line = explode(":", $line);
            if ($line[0] == $key) {
              return $line[1];
            }
          }
        }
        
        // Create map with request parameters
        $params = array ('query' => 'setpassword','inputs' => [$key]);
        
        // Build Http query using params
        $query = http_build_query ($params);
        
        // Create Http context details
        $contextData = array (
                    'method' => 'POST',
                    'header' => "Connection: close\r\n".
                                "Content-Length: ".strlen($query)."\r\n",
                    'content'=> $query );
        
        // Create context resource for our request
        $context = stream_context_create (array ( 'http' => $contextData ));
        
        // Read page rendered as result of your POST request
        $result =  file_get_contents (
                      'http://'.$ip.'/api.php',
                      false,
                      $context);
        
        if ($result == "password-updated") {
            // If the password has been updated, log the changes
            require_once('query_passwords.php');
            createPassword($ip, $key);
        }

        echo "<br><br>Adding to db";
        // prepare and bind
        $stmt = $conn->prepare("INSERT INTO `Devices` (`ip`, `Name`, `Deployment`, `Recording`, `Type`) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $pushIP, $pushName, $pushDeployment, $pushRecording, $deviceType);
        $pushIP = htmlspecialchars($_POST["ip"], ENT_QUOTES);
        $pushName = htmlspecialchars($_POST["name"], ENT_QUOTES);
        $pushDeployment = htmlspecialchars($_POST["deployment"], ENT_QUOTES);
        $pushRecording = htmlspecialchars($_POST["recordings"], ENT_QUOTES);
        $deviceType = $json["type"];
        

        $stmt->execute();

        echo "<br>Added sucessfully to the DB";
        // header("Location: adopt-success.php?adoptedip=".$pushIP);
    } else {
        $status = "0";

        echo "<br>The device did not have a verification code<br>";
        echo "This has even been misconfigured or the IP is not linked to a device";
        
    }
}



$conn->close();
?>