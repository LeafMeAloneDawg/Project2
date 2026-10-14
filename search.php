<?php
/*
filename: search.php
author: Mohammad Aquib
created: 14/10/2026
last modified: 14/10/2026
description: Job search logic, included at the top of jobs.php. Reads the search term from
             the search bar and gets the matching jobs from the database (or every job when
             there is no search term). It prints no HTML itself; jobs.php shows the results.
             After this file runs, jobs.php can use:
               $search     the search term ("" when not searching)
               $result     the jobs to show
               $job_count  how many jobs are in $result

GenAI acknowledgement: the search query in this file was drafted with the assistance of
GenAI, then reviewed, tested and edited by Mohammad Aquib.
*/

# This file is only meant to be included by jobs.php. Opened directly in the browser, #
# it would print nothing, so send the visitor to the jobs page instead #
if (basename($_SERVER['SCRIPT_FILENAME']) === "search.php") {
    header("Location: jobs.php");
    exit();
}

# Connects to the database and creates/fills the jobs and users tables if needed #
require_once("db_setup.php");

# Search term from the search bar (GET, so the results page can be bookmarked or shared) #
$search = trim($_GET['search'] ?? "");
if (strlen($search) > 100) {
    $search = substr($search, 0, 100);   # keep the search term to a sensible length #
}

if ($search !== "") {
    # % and _ are wildcards in LIKE, so escape them to treat them as ordinary characters #
    $like = "%" . addcslashes($search, "%_\\") . "%";

    # Prepared statement: the search text is never pasted into the SQL, which stops SQL injection #
    $stmt = mysqli_prepare($dbconn, "SELECT $job_columns FROM jobs
        WHERE title LIKE ? OR job_reference_number LIKE ? OR short_description LIKE ?
           OR responsibilities LIKE ? OR essential_requirements LIKE ? OR preferable_requirements LIKE ?
        ORDER BY title");
    mysqli_stmt_bind_param($stmt, "ssssss", $like, $like, $like, $like, $like, $like);
} else {
    # No search term: every job #
    $stmt = mysqli_prepare($dbconn, "SELECT $job_columns FROM jobs ORDER BY title");
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$job_count = mysqli_num_rows($result);
