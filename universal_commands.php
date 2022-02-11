<?php
    // Check for adoption
    require_once('query_passwords.php');
    if (!getPass("controller")) {
        // No password for controller registered
        // This means that it is not fully set up yet!
        echo '
        <script>
        let form = document.createElement("form");
        form.action = "setup.php";
        form.method = "POST";
        let input = document.createElement("input");
        input.value = "first-time-setup";
        input.name = "customfile";

        document.body.appendChild(form);
        form.appendChild(input);
        form.submit();
        </script>
        ';
    };
?>