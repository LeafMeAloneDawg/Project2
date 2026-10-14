<?php
/*
filename: auth_check.php
author: Mohammad Aquib
created: 13/10/2026
last modified: 14/10/2026
description: Gate for protected pages. Put it on the very first line of manage.php:
             <?php require_once("auth_check.php"); ?>
             Visitors who are not logged in are sent to login.php.

GenAI acknowledgement: the code in this file was drafted with the assistance of GenAI,
then reviewed, tested and edited by Mohammad Aquib.
*/

require_once("session_init.php");

$idle_limit = 1800;   # log out after 30 minutes without activity #

# Not logged in: go to the login page #
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

# Logged in but idle for too long: end the session and ask them to log in again #
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $idle_limit) {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php?timeout=1");
    exit();
}

$_SESSION['last_activity'] = time();

# Stop the browser caching this page, so the Back button cannot show it after logging out #
header("Cache-Control: no-store, no-cache, must-revalidate");