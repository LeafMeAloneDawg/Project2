<?php
/*
filename: db_setup.php
author: Mohammad Aquib
created: 13/10/2026
last modified: 13/10/2026
description: Creates the jobs and users tables if they do not exist yet and fills them
             with starting data. Included by jobs.php and login.php.

GenAI acknowledgement: the structure of this file
was drafted with the assistance of GenAI, then reviewed, tested and edited by Mohammad Aquib.
*/

# Connect to the database (credentials live in settings.php, connection code in db_connect.php) #
require_once("db_connect.php");

# Use utf8mb4 so characters such as the en dash in the salaries are stored and read back correctly #
mysqli_set_charset($dbconn, "utf8mb4");


#              #
# jobs table   #
#              #

# One row per job. Responsibilities and requirements are stored as text with one item per line #
# and split into <li> items by jobs.php #
$create_jobs_query = "CREATE TABLE IF NOT EXISTS jobs (
    job_reference_number VARCHAR(6) NOT NULL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    short_description TEXT NOT NULL,
    salary VARCHAR(100) NOT NULL,
    reports_to VARCHAR(100) NOT NULL,
    responsibilities TEXT NOT NULL,
    essential_requirements TEXT NOT NULL,
    preferable_requirements TEXT NOT NULL
) DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($dbconn, $create_jobs_query)) {
    die("<p>Unable to create the jobs table.</p>");
}