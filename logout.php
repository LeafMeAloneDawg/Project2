<?php
/*
filename: logout.php
author: Mohammad Aquib
created: 13/10/2026
last modified: 13/10/2026
description: Ends the HR manager's session and returns them to the login page.
*/

require_once("session_init.php");

# Remove all session data #
$_SESSION = [];

# Delete the session cookie in the browser as well #
if (ini_get("session.use_cookies")) {
    $cookie = session_get_cookie_params();
    setcookie(session_name(), "", time() - 42000,
        $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
}

session_destroy();

header("Location: login.php?loggedout=1");
exit();