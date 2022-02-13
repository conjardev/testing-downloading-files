<!DOCTYPE html>
<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"></script>

    <?php
        $page = htmlspecialchars($_GET["p"], ENT_QUOTES);
        if ($page == "machines") {
            echo '
            <link rel="stylesheet" href="camera-core/styles.css">
            <script src="camera-core/script.js"></script>
            ';
        }
    ?>

    <title>Document</title>
</head>
<body>
    <?php
        require("universal_commands.php"); 
        if (!$page) {
            header("Location: ./?p=home");
        };

        include('controllerInfo.php');

        $servername = "localhost:3306";
        $username = getControllerInfo("username");
        $password = getPass("controller");
        $dbname = "robots";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        header("Location: errorHandler.php?s=control-center&e=".$conn->connect_error);
        die("Connection failed: " . $conn->connect_error);
        }
    ?>
    <?php
        $status = htmlspecialchars($_GET["stat"], ENT_QUOTES);
        if ($status == "0") {
            // No verif code
            echo "<h5>Device was even misconfigured or is not supported</h5>";
        } else if ($status == "1") {
            // Succesfully adopted
            echo "<h5>Device was sucessfully adopted</h5>";
        } else if ($status == "2") {
            // Already adopted
            echo "<h5>This device has already been adopted</h5>";
        }
    ?>

    <div class="sidebar">
        <?php
            $menuopts = [
            //  [icon name, page name, tooltip]
                ["home", "home", "Home"],
                ["security", "machines", "Machines"],
                ["place", "map", "Live Map"]
            ];

            foreach ($menuopts as $option) {
                echo '<a title="'.$option[2].'" onclick="setpage(&quot;'.$option[1].'&quot;)" class="material-icons-outlined">'.$option[0].'</a>';
            }
        ?>
    </div>

    <div class="content">
        <div style="grid-area: boxleft;" class="box">
            <?php 

            if ($page == "home") {
            echo '
                <div id="centered">
                    <h2>No floorplan set up</h2>
                    <button onclick="openWizard(&quot;map-setup&quot;)">Configure Now</button>
                </div>
                ';
            }

            ?>
        </div>

        <div style="grid-area: boxright;" class="box">
            <?php
                if ($page == "home") {
                    echo '
                    <h1>Devices</h1>
                    <h3>See your devices and adopt new ones</h3>
                    <br>
                    <h3>Adopt a new device:</h3>
                    <form id="adopt" action="setup.php" method="POST">
                        <input type="text" placeholder="Pi IP (192.168.1...)" name="ip">
                        <input type="submit" value="Adopt">
                    </form>
                    <br>
                    <h3>See your connected devices</h3>
                    ';

                    $sql = "SELECT * FROM `Devices`";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<h4>".$row["Name"].": ".$row["ip"]."</h4>";
                        }
                    }
                }
                
            ?>
        </div>

        <div style="grid-area: box;" class="box">
            <?php 
            if ($page == "home") {
                echo '
                <form id="voice-form"> 
                    <select onchange="speak()" name="m">
                        <option selected disabled>SELECT MESSAGE</option>
                ';
                $arr = [
                    array("name"=>"Custom", "value"=>
                    [
                        ["Wow Hello", "custom1"],
                        ["I am talking haha", "custom2"]
                    ]
                    ),
                    array("name"=>"Greeting", "value"=>
                    [
                        ["Good morning", "greetMorning"],
                        ["Good afternoon", "greetAfternoon"],
                        ["Good evening", "greetEvening"],
                        ["Hello", "hello"],
                        ["Hi", "hi"],
                        ["Good day to you", "goodDay"],
                        ["Excuse me!", "pardon1"],
                        ["Pardon me!", "pardon2"]
                    ]
                    ),
                    array("name"=>"Emergency", "value"=>
                    [
                        ["Step away from this machine", "away1"],
                        ["Step away from this machine now", "away2"],
                        ["Seriously step away", "away3"],
                        ["5,4,3,2,1", "countdown"],
                        ["Authorities have been notified", "notify"],
                    ]
                    )
                ];

                foreach ($arr as $group) {
                    echo "<optgroup label='".$group["name"]."'>";
                    foreach ($group["value"] as $item) {
                        echo "<option value='".$item[0]."'>".$item[0]."</option>";
                    }
                    echo "</opptgroup>";
                }
                            
                echo '
                    </select>
                </form>
                ';
            }
            ?>
        </div>

    </div>


    <script>
        function speak() {
            let voiceLine
            var data = $('#voice-form').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            voiceLine = obj["m"]
            document.getElementById("voice-form").children[0].children[0].selected = true
            }, {});

            console.log(voiceLine)
        }

        function setpage(page) {
            window.location.href = "./?p=" + page
            console.log(page)
        }

        function openWizard(customFile) {
            let form = document.createElement("form")
            form.action = "setup.php"
            form.method = "POST"
            let input = document.createElement("input")
            input.value = customFile
            input.name = "customfile"

            document.body.appendChild(form)
            form.appendChild(input)
            form.submit();

        }

        let page = '<?php echo $page; ?>';
        if (page == "machines") {
            let boxes = document.getElementsByClassName("box")
            for (i=0; i<boxes.length; i++) {
                boxes[i].style.display="hidden";
            }
            document.getElementsByClassName("content")[0].style.display = "unset"
        } else {
            let boxes = document.getElementsByClassName("box")
            for (i=0; i<boxes.length; i++) {
                boxes[i].style.display="unset";
            }
            document.getElementsByClassName("content")[0].style.display = "grid"
        }

        <?php
            if ($page == "machines") {
                $sql = "SELECT * FROM `Devices`";
                $result = $conn->query($sql);
                $num = $result->num_rows;
                $info = [];

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        array_push($info, $row);
                    }
                }
                
                echo 'let info = '.json_encode($info).';';
                echo '<br>console.log(info)<br>';

                echo '
                window.addEventListener("load", function () {

                    // select parent of dish
                    let scenary = document.getElementsByClassName("content")[0];
                    scenary.innerHTML = "";

                    // create dish
                    let dish = new Dish(scenary);
        
                    // render dish
                    dish.append();
        
                    for (x=0; x<ifo.length; x++) {
                        dish.create();
                    }

                    // resize the cameras
                    dish.resize()
        
                    // resize event of window
                    window.addEventListener("resize", function () {
        
                        // resize event to dimension cameras
                        dish.resize();
        
                    });
        
                }, false);
                ';
            }
        ?>
        
    </script>

    <style>
        body {
            overflow-x: hidden;

            margin: 0;
            height: 100vh;
            display: grid;
            grid-template-columns: 60px auto;
            grid-template-rows: 100%;

            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

        .sidebar {
            background-color: #f5f2f2;
            border-right: #d7d7d7 solid 1px;
            padding: 5px;
            box-sizing: border-box;
        }

        .sidebar a {
            width: 100%;
            text-align: center;
            font-size: 2em;
            cursor: pointer;
            color: #707070;
        }

        .content {
            width: 100%;
            height: 100%;
            
            display: grid;
            grid-template-areas: 
            'boxleft boxright'
            'box box';

            grid-template-rows: 50% 50%;
            grid-template-columns: 50% 50%;
        }

        .box {
            padding: 10px;
            border: #e7e7e7 solid 1px;
        }

        .box #centered {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        input, button {
            border: solid #d7d7d7 1px;
            padding: 5px;
        }

        button:active {
            background-color: #c8c8c8;
        }

        input[type="submit"], button {
            cursor: pointer;
        }

        h1 {
            font-weight: bolder;
            margin: 3px;
        }

        h3 {
            margin: 3px;
            color: #747474;
            font-weight: lighter;
            font-size: 0.9em;
        }

        h4 {
            margin: 3px;
            color: #747474;
            font-weight: light;
            font-size: 0.8em;
        }
    </style>
</body>
</html>