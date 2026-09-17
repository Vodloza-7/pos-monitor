<?php

if (session_id() == "") {
    session_start();
}

if (!isset($_SESSION["monitor_user_id"])) {

    header("Location: login.php");
    exit;
}

?>