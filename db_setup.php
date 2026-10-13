<?php
/*
filename: db_setup.php
author: Mohammad Aquib
created: 13/10/2026
last modified: 13/10/2026
description: Creates the jobs and users tables if they do not exist yet and fills them
             with starting data. Included by jobs.php and login.php.

GenAI acknowledgement: the structure of this file (prepared statements, password hashing)
was drafted with the assistance of GenAI, then reviewed, tested and edited by Mohammad Aquib.
*/

# Connect to the database (credentials live in settings.php, connection code in db_connect.php) #
require_once("db_connect.php");

# Use utf8mb4 so characters such as the en dash in the salaries are stored and read back correctly #
mysqli_set_charset($dbconn, "utf8mb4");