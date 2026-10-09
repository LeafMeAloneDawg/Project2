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

# Validate date of birth #
if (empty($date_of_birth)) {
    $errors[] = "Date of birth is required.";
} elseif (!preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $date_of_birth)) {
    $errors[] = "Date of birth must be entered as dd/mm/yyyy.";
} else {
    $date_parts = explode("/", $date_of_birth);

    $day = $date_parts[0];
    $month = $date_parts[1];
    $year = $date_parts[2];

    if (!checkdate($month, $day, $year)) {
        $errors[] = "Please enter a valid date of birth.";
    }
}

# Validate gender #
$valid_genders = array(
    "male",
    "female",
    "other",
    "prefer_not_to_say"
);

if (empty($gender)) {
    $errors[] = "Gender is required.";
} elseif (!in_array($gender, $valid_genders)) {
    $errors[] = "Please select a valid gender.";
}

# Validate street address #
if (empty($street_address)) {
    $errors[] = "Street address is required.";
} elseif (strlen($street_address) > 40) {
    $errors[] = "Street address must not be more than 40 characters.";
}

# Validate suburb #
if (empty($suburb)) {
    $errors[] = "Suburb or town is required.";
} elseif (strlen($suburb) > 40) {
    $errors[] = "Suburb or town must not be more than 40 characters.";
}

# Validate state #
$valid_states = array(
    "victoria",
    "new_south_wales",
    "queensland",
    "northern_territory",
    "western_australia",
    "south_australia",
    "tasmania",
    "australian_capital_territory"
);

if (empty($state)) {
    $errors[] = "State is required.";
} elseif (!in_array($state, $valid_states)) {
    $errors[] = "Please select a valid state.";
}

# Validate postcode #
if (empty($postcode)) {
    $errors[] = "Postcode is required.";
} elseif (!preg_match("/^[0-9]{4}$/", $postcode)) {
    $errors[] = "Postcode must contain exactly 4 numbers.";
}

# Validate email #
if (empty($email)) {
    $errors[] = "Email is required.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Please enter a valid email address.";
}

# Validate phone number #
if (empty($phone)) {
    $errors[] = "Phone number is required.";
} elseif (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors[] = "Phone number must contain between 8 and 12 numbers.";
}

# Validate & process skills #
$skills = "";

$valid_skills = array(
    "communication",
    "teamwork",
    "leadership",
    "technical",
    "problem-solving",
    "time-management"
);

if (isset($_POST["skills"]) && is_array($_POST["skills"])) {
    $selected_skills = array();

    foreach ($_POST["skills"] as $skill) {
        $skill = clean_input($skill);

        if (in_array($skill, $valid_skills)) {
            $selected_skills[] = $skill;
        }
    }

    $skills = implode(", ", $selected_skills);
}

// If there are errors, show them and stop
if (count($errors) > 0) {
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Application Error</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>

    <body class="apply-page">

        <nav>
            <?php include("nav.inc"); ?>
        </nav>

         <main class="apply-main">

            <div class="apply_intro_box">

                <h1>Application Error</h1>

                <p>Please fix the following problems:</p>

                <ul>

                    <?php
                    foreach ($errors as $error) {
                        echo "<li>$error</li>";
                    }
                    ?>

                </ul>

                <p><a href="apply.php">Return to the application form</a></p>

            </div>

        </main>

        <footer>
            <?php include("footer.inc"); ?>
        </footer>

    </body>
    </html>

    <?php

    mysqli_close($dbconn);
    exit();
}

# Convert date from dd/mm/yyyy into MySQL yyyy-mm-dd format #
$date_parts = explode("/", $date_of_birth);

$database_date =
    $date_parts[2] . "-"
    . $date_parts[1] . "-"
    . $date_parts[0];

# Make data values safe for the SQL query #
$job_reference_number = mysqli_real_escape_string($dbconn, $job_reference_number);
$first_name = mysqli_real_escape_string($dbconn, $first_name);
$last_name = mysqli_real_escape_string($dbconn, $last_name);
$database_date = mysqli_real_escape_string($dbconn, $database_date);
$gender = mysqli_real_escape_string($dbconn, $gender);
$street_address = mysqli_real_escape_string($dbconn, $street_address);
$suburb = mysqli_real_escape_string($dbconn, $suburb);
$state = mysqli_real_escape_string($dbconn, $state);
$postcode = mysqli_real_escape_string($dbconn, $postcode);
$email = mysqli_real_escape_string($dbconn, $email);
$phone = mysqli_real_escape_string($dbconn, $phone);
$skills = mysqli_real_escape_string($dbconn, $skills);
$other_skills = mysqli_real_escape_string($dbconn, $other_skills);

# Add application to the eoi table #
$insert_query = "INSERT INTO eoi (
    job_reference_number,
    first_name,
    last_name,
    date_of_birth,
    gender,
    street_address,
    suburb,
    state,
    postcode,
    email,
    phone,
    skills,
    other_skills
)

VALUES (
    '$job_reference_number',
    '$first_name',
    '$last_name',
    '$database_date',
    '$gender',
    '$street_address',
    '$suburb',
    '$state',
    '$postcode',
    '$email',
    '$phone',
    '$skills',
    '$other_skills'
)";

$insert_result = mysqli_query($dbconn, $insert_query);

# Check whether application was saved #
if ($insert_result) {
    $eoi_number = mysqli_insert_id($dbconn);

} else {
    die("<p>There was an error submitting your application.</p>");
}

mysqli_close($dbconn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Xander Rode-Bramanis">
    <meta name="description" content="Job application confirmation">
    <title>Application Submitted</title>

    <link rel="stylesheet" href="styles/styles.css">
</head>

<body class="apply-page">

    <nav>
        <?php include("nav.inc"); ?>
    </nav>




?>