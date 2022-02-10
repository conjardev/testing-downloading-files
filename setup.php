 <!DOCTYPE html>
<!-- Connect to the database and verify the IP -->


<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Set up your device</title>
</head>
<body>
    <div id="image-box">
    </div>
    <div id="steps">
        <div id="instructions">
            <h3>Step 0/0</h3>
            <h1>Oopsies!</h1>
            <h2>This form has not loaded correctly</h2>
            <br>
            <h3>This may be our problem, or it may be yours.</h3>
            <form>
                <input name="name">
                <input type="submit">
            </form>
        </div>
    </div>

    
    <script>
        const deviceIp = "<?php echo htmlspecialchars($_POST["ip"], ENT_QUOTES) ?>";
        const images = document.getElementById("image-box");
        const instructions = document.getElementById("instructions");

        const order = [<?php 
    
        $fileName = "";
        if (!$_POST["customfile"]) {
            $sql = "SELECT * FROM `Wizards` WHERE `Device` = '".$json["type"]."'";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $fileName = $row["IndexName"];
            }
            }
        } else {
            $fileName = htmlspecialchars($_POST["customfile"], ENT_QUOTES);
        }
        
        // echo "wizards/".$step.".txt";
        $filePath = "wizards/".$fileName.".txt";
        echo file_get_contents($filePath);
        
        ?>];

        let formInfo = []
        let currentStep = 0;

        function submit(e) {
            let data = $("form").serializeArray();
            formInfo.push(JSON.stringify(data))
            // document.getElementById("dingus").innerHTML = formInfo

                
            drawStep(currentStep)
            e.preventDefault(e); 
        }
        
        function drawStep(step) {
            currentStep = currentStep + 1
            images.innerHTML = ""
            let newHtml = ""
            for (i=0; i<order[step].length; i++) {
                if (i > 0) {
                    if (order[step][i] == "%s") {
                        newHtml = newHtml + "Step " + (step + 1) + " of " + order.length;
                    } else if (order[step][i] == "%overview") {
                        for (let x=0; x < formInfo.length; x++) {
                            let info = formInfo[x]
                            info = JSON.parse(info)[0]

                            let name = info["name"]
                            let value = info["value"]

                            function capitalizeFirstLetter(string) {
                            return string.charAt(0).toUpperCase() + string.slice(1);
                            }

                            if(name.toLowerCase() == "password") {
                                newHtml = newHtml + "<h3>" + capitalizeFirstLetter(name) + " - " + ("*".repeat(value.length)) + "</h3>"
                            } else {
                                newHtml = newHtml + "<h3>" + capitalizeFirstLetter(name) + " - " + capitalizeFirstLetter(value) + "</h3>"
                            }
                            
                        } 
                    } else if (order[step][i] == "%ip") {
                        newHtml = newHtml + "<h3>"+deviceIp+"</h3>"
                    } else if (order[step][i] == "%list-overview") {
                        for (let x=0; x < formInfo.length; x++) {
                            let info = formInfo[x]
                            info = JSON.parse(info)[0]

                            let name = info["name"].split(" ")
                            name = name.join('')

                            newHtml = newHtml + "<input style='display: none' name='"+name+"' value='"+info["value"]+"'>"
                        }
                        newHtml = newHtml + "<input style='display: none' name='ip' value='"+deviceIp+"'>"
                    } else {
                        newHtml = newHtml + order[step][i];
                    }
                } else {
                    images.innerHTML = "<img src='" + order[step][i] + "'>"
                }
            }
            instructions.innerHTML = newHtml
            $("form").submit(function(e){submit(e)});
        }

        drawStep(currentStep)
    </script>

    <style>
        body {
            color: #323232;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;

            height: 100vh;
            overflow: hidden;
            margin: 0;

            display: grid;
            grid-template-rows: 100%;
            grid-template-columns: 33% 28%;
        }

        #image-box {
            border-width: 1px;
            border-right-style: solid;
            border-image: linear-gradient(to bottom, #ffffff 10%,#c2c2c2 45%,#c2c2c2 55%,#fffefe 90%) 1 stretch;
            
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #image-box img {
            width: 100%;
            height: 100%;
            
            object-fit: contain;
        }

        #steps {
            display: flex;
            align-items: center;
            padding-left: 50px;
        }

        h1 {
            font-weight: bolder;
        }

        h3 {
            color: #747474;
            font-weight: lighter;
            font-size: 0.9em;
        }

        input {
            border: solid #d7d7d7 1px;
            padding: 5px;
        }

        select {
            border: solid #d7d7d7 1px;
            padding: 5px;
        }

        input[type="submit"] {
            position: absolute;
            bottom: 10px;
            right: 10px;
            border-radius: 5px;
            border: none;

            padding: 10px;

            font-size: 15px;
            font-weight: 600;
            color: #2180ff;
            background-color: #e2f5ff;

            height: 40px;
            width: 75px;
            cursor: pointer;
        }

        @media screen and (max-width: 1025px) {
            body {
                display: grid;
                grid-template-columns: 100%;
                grid-template-rows: 33% 35%;
            }

            #image-box {
                border-width: 1px;
                border-bottom-style: solid;
                border-image: linear-gradient(to right, #ffffff 10%,#c2c2c2 45%,#c2c2c2 55%,#fffefe 90%) 1 stretch;
            }

            #steps {
                padding-left: unset;
                justify-content: center;
                text-align: center;
            }
        }
    </style>
</body>
</html>
