<?php
    function getPass($ip) {;
        $file = json_decode(file_get_contents("configuration/passwords.json"));
        $getPass = false;

        foreach ($file as $value) {
            $json = json_encode($value);
            foreach ((json_decode($json)) as $key => $value) {
                if ($key == 'ip') {
                    if ($value == $ip) {
                        $getPass = True;
                    } else {
                        // echo "<br>Expected ".$ip." got ".$value;
                    }
                } else if ($key == 'password' && $getPass) {
                    // echo "<br>Password = ".$value;
                    $getPass = false;
                    return $value;
                }
            }
            // print_r(json_decode(($json)));
        }
    };

    function createPassword($ip, $password) {
        if (!getPass(($ip))) {
            $passfile = "configuration/passwords.json";
            $data = json_decode(file_get_contents($passfile));
        
            $index = count($data);
                
            $data[$index] = array(
                "ip" => $ip,
                "password" => $password
            );
                
            $fp = fopen($passfile, "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    };
?>