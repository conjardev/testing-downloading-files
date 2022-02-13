<?php
// Error Handler
// When an error occurs, the user is sent here with two pieces of data, the source,
// and a rough idea of the kind of error.
$source = htmlspecialchars($_GET["s"], ENT_QUOTES);
$error = htmlspecialchars($_GET["e"], ENT_QUOTES);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Error</title>
</head>
<body>
    <h1>Oh no! An error!</h1>
    <h2>Something has gone wrong.</h2>
    <h3>Here's what we know:</h3>
    <h3>Source = <?php echo $source; ?></h3>
    <h3>Error = <?php echo $error; ?></h3>
    <br>
    <h4>If this error persists, please start an <a href="https://github.com/conjardev/testing-downloading-files/issues">issue on the GitHub<a></h4>
    <button onclick="window.location.href = './'">Go to control center</button>
    <h5>If you error was on the control center, then, this is your new home</h5>
</body>
</html>

<style>  
    body {
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        color: #2d2d2d;
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

    button {
            height: 50px;
            border: solid #d7d7d7 1px;
            padding: 5px;
            font-weight: bolder;
            cursor: pointer;
    }
</style>