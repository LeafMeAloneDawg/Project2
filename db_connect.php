<?php 
    require_once("settings.php");
    $dbconn = mysqli_connect($host, $user, $pwd, $sql_db);

    if (!$dbconn) {
        die("<p>Unable to connect to the database.</p>");
    }