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

# Create the eoi table if it does not already exist #
# It's the same info requested on the form #
# (the assignment requires this table to be created in the process_eoi.php file) #
$create_table_query = "CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_reference_number VARCHAR(6) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender VARCHAR(20) NOT NULL,
    street_address VARCHAR(40) NOT NULL,
    suburb VARCHAR(40) NOT NULL,
    state VARCHAR(30) NOT NULL,
    postcode CHAR(4) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    skills VARCHAR(255),
    other_skills TEXT,
    status ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New'
)";
$table_result = mysqli_query($dbconn, $create_table_query);

if (!$table_result) {
    die("<p>Unable to create the EOI table.</p>");
}

# Get and clean form data #
$job_reference_number = clean_input($_POST["job_reference_number"] ?? "");
$first_name = clean_input($_POST["first_name"] ?? "");
$last_name = clean_input($_POST["last_name"] ?? "");
$date_of_birth = clean_input($_POST["date_of_birth"] ?? "");
$gender = clean_input($_POST["gender"] ?? "");
$street_address = clean_input($_POST["street_address"] ?? "");
$suburb = clean_input($_POST["suburb"] ?? "");
$state = clean_input($_POST["state"] ?? "");
$postcode = clean_input($_POST["postcode"] ?? "");
$email = clean_input($_POST["email"] ?? "");
$phone = clean_input($_POST["phone"] ?? "");
$other_skills = clean_input($_POST["other_skills"] ?? "");


#             #
# Validations #
#             #

# Store validation errors #
$errors = array();

# Validate job reference number #
if (empty($job_reference_number)) {
    $errors[] = "Job reference number is required.";
} elseif (!preg_match("/^[A-Za-z0-9]{6}$/", $job_reference_number)) {
    $errors[] = "Job reference number must be exactly 6 letters or numbers.";
}

# Validate first name #
if (empty($first_name)) {
    $errors[] = "First name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
    $errors[] = "First name must contain letters only and be no more than 20 characters.";
}

# Validate last name #
if (empty($last_name)) {
    $errors[] = "Last name is required.";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
    $errors[] = "Last name must contain letters only and be no more than 20 characters.";
}



?>