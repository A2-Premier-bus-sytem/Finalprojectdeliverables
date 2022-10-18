<?php
    session_start();

    if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"])
    {
        if (isset($_SESSION["user_role"]) && ($_SESSION["user_role"] == "general"))
        {
            header("location: normal/index.php", TRUE, 302);
        } else if (isset($_SESSION["role"]) && ($_SESSION["role"] == "admin")) {
            header("location: admin/bus.php");
        }
    }
    
    $loggedIn = false;
?>