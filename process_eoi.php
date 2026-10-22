<?php

# Stop users from opening this page directly #
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: apply.php");
    exit();
}

# Clean form input #
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

# Connect to database and make sure required tables exist #
require_once("db_setup.php");


# Get and clean form data #
$job_reference_number = strtoupper(
    clean_input($_POST["job_reference_number"] ?? "")
);

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

$errors = array();


# Validate job reference number #
if (empty($job_reference_number)) {

    $errors[] = "Job reference number is required.";

} elseif (!preg_match("/^[A-Za-z0-9]{6}$/", $job_reference_number)) {

    $errors[] = "Job reference number must be exactly 6 letters or numbers.";
}


# Check that the job reference exists in the jobs table #
if (
    !empty($job_reference_number) &&
    preg_match("/^[A-Za-z0-9]{6}$/", $job_reference_number)
) {

    $job_check = mysqli_prepare(
        $dbconn,
        "SELECT job_reference_number
         FROM jobs
         WHERE job_reference_number = ?"
    );

    mysqli_stmt_bind_param(
        $job_check,
        "s",
        $job_reference_number
    );

    mysqli_stmt_execute($job_check);
    mysqli_stmt_store_result($job_check);

    if (mysqli_stmt_num_rows($job_check) == 0) {
        $errors[] = "The job reference number does not exist.";
    }

    mysqli_stmt_close($job_check);
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


# Validate and process skills #
$skills = "";

$valid_skills = array(
    "communication",
    "teamwork",
    "leadership",
    "technical",
    "problem-solving",
    "time-management"
);

if (!isset($_POST["skills"]) || !is_array($_POST["skills"])) {

    $errors[] = "Please select at least one skill.";

} else {

    $selected_skills = array();

    foreach ($_POST["skills"] as $skill) {

        $skill = clean_input($skill);

        if (in_array($skill, $valid_skills)) {
            $selected_skills[] = $skill;
        }
    }

    if (count($selected_skills) == 0) {
        $errors[] = "Please select at least one skill.";
    }

    $skills = implode(", ", $selected_skills);
}


# If there are errors, show them and stop #
if (count($errors) > 0) {
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Xander Rode-Bramanis">
    <meta name="description" content="Job application error">
    <title>Application Error</title>

    <link rel="stylesheet" href="styles/styles.css">
</head>

<body class="apply-page">

    <?php include("header.inc"); ?>

    <nav>
        <?php include("nav.inc"); ?>
    </nav>

    <main id="main-content" class="apply-main" tabindex="-1">

        <div class="apply_intro_box">

            <h1>Application Error</h1>

            <p class="error-intro">Please fix the following problems:</p>

            <ul class="error-list">
            <?php
            foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
            }
            ?>
            </ul>

            <p class="error-return">
                <a href="apply.php">Return to the application form</a>
            </p>

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


# Add application to the eoi table using a prepared statement #
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
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$insert_stmt = mysqli_prepare($dbconn, $insert_query);

mysqli_stmt_bind_param(
    $insert_stmt,
    "sssssssssssss",
    $job_reference_number,
    $first_name,
    $last_name,
    $database_date,
    $gender,
    $street_address,
    $suburb,
    $state,
    $postcode,
    $email,
    $phone,
    $skills,
    $other_skills
);

$insert_result = mysqli_stmt_execute($insert_stmt);


# Check whether application was saved #
if ($insert_result) {

    $eoi_number = mysqli_insert_id($dbconn);

} else {

    mysqli_stmt_close($insert_stmt);
    mysqli_close($dbconn);

    die("<p>There was an error submitting your application.</p>");
}

mysqli_stmt_close($insert_stmt);
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

    <?php include("header.inc"); ?>

    <nav>
        <?php include("nav.inc"); ?>
    </nav>

    <main id="main-content" class="apply-main" tabindex="-1">

        <div class="apply_intro_box">

            <h1>Application Submitted</h1>

            <p>Your application has been submitted successfully.</p>

            <p>
                Your EOI number is:
                <strong><?php echo $eoi_number; ?></strong>
            </p>

            <p>Please keep this number for your records.</p>

        </div>

    </main>

    <footer>
        <?php include("footer.inc"); ?>
    </footer>

</body>

</html>