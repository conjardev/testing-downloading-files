<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

function updateControllerInfo($name, $upFreq, $upTime, $uname) {
        $update = array(
            "name"=>htmlspecialchars($name, ENT_QUOTES),
            "update-frequency"=>htmlspecialchars($upFreq, ENT_QUOTES),
            "update-time"=>htmlspecialchars($upTime, ENT_QUOTES),
            "username"=>htmlspecialchars($uname, ENT_QUOTES)
        );
    
        $file = "configuration/controllerInfo.json";
        $data = json_decode(file_get_contents($file));
    
        foreach ($update as $key=>$value) {
            if ($value) {
                echo $key." = ".$value."<br>";
                
                $data->$key = $value;
                
                $fp = fopen($file, "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        }
    };

    function getControllerInfo($query) {
        $file = "configuration/controllerInfo.json";
        $data = json_decode(file_get_contents($file));

        foreach ($data as $key=>$value) {
            if ($key == $query) {
                return $value;
            } else {
                // echo $key." is not ".$value."<br>";
            }
        }
    }
?>