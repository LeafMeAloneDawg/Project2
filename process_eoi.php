<?php

# Stop users from opening this page directly #
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: apply.php");
    exit();
}

# Clean form input (trim, stripslashes, htmlspecialchars) #
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

# Connect to database #
require_once("settings.php");
$dbconn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$dbconn) {
    die("<p>Unable to connect to the database.</p>");
}

?>