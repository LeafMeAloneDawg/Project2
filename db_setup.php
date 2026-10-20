<?php
/*
filename: db_setup.php
author: Mohammad Aquib
created: 13/10/2026
last modified: 14/10/2026
description: Creates the jobs and users tables if they do not exist yet and fills them
             with starting data. Included by search.php (for jobs.php) and login.php.

GenAI acknowledgement: the structure of this file (prepared statements, password hashing)
was drafted with the assistance of GenAI, then reviewed, tested and edited by Mohammad Aquib.
*/

# Connect to the database (credentials live in settings.php, connection code in db_connect.php) #
require_once("db_connect.php");

# Use utf8mb4 so characters such as the en dash in the salaries are stored and read back correctly #
mysqli_set_charset($dbconn, "utf8mb4");

# Adds a column to a table that was created before the column existed (for example a teammate's #
# database made with an older version of this file). Only ever called with fixed names written #
# below, never with user input, so building the SQL from the parameters is safe here #
function add_column_if_missing($dbconn, $table, $column, $definition) {
    $check = mysqli_query($dbconn, "SHOW COLUMNS FROM $table LIKE '$column'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($dbconn, "ALTER TABLE $table ADD $column $definition");
    }
}


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
    preferable_requirements TEXT NOT NULL,
    closing_date DATE NOT NULL
) DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($dbconn, $create_jobs_query)) {
    die("<p>Unable to create the jobs table.</p>");
}

# Older jobs tables have no closing date yet: add it and give existing rows the Part 1 date #
add_column_if_missing($dbconn, "jobs", "closing_date", "DATE NOT NULL DEFAULT '2026-10-25'");

# Columns read by jobs.php and search.php, kept in one place so both pages stay the same #
$job_columns = "job_reference_number, title, short_description, salary, reports_to,
                responsibilities, essential_requirements, preferable_requirements, closing_date";

# Only add the starting jobs when the table is empty #
$count_result = mysqli_query($dbconn, "SELECT COUNT(*) AS total FROM jobs");
$count_row = mysqli_fetch_assoc($count_result);

if ($count_row['total'] == 0) {

    # The three jobs from the Part 1 jobs page #
    $starting_jobs = [
        [
            'ref' => 'CSW214',
            'title' => 'Community Support Worker',
            'description' => 'Provide direct, practical support to young people and families accessing our community programs, helping them connect with local services and build long-term wellbeing.',
            'salary' => '$68,000 – $74,000 per annum (pro rata for part-time)',
            'reports_to' => 'Community Programs Manager',
            'closing_date' => '2026-10-25',
            'responsibilities' => [
                'Complete intake interviews and assess each client\'s needs.',
                'Develop and review individual support plans with clients.',
                'Coordinate referrals to housing, health and education services.',
                'Maintain accurate, confidential case notes and records.',
                'Attend weekly team case-review meetings.'
            ],
            'essential' => [
                'Diploma of Community Services or a related qualification.',
                'Current Working with Children Check and National Police Check.',
                'At least one year of experience in a frontline support role.',
                'Current Victorian driver\'s licence.'
            ],
            'preferable' => [
                'Experience supporting culturally and linguistically diverse communities.',
                'Current First Aid and CPR certificate.',
                'Knowledge of Melbourne\'s youth and family service system.'
            ]
        ],
        [
            'ref' => 'VEC091',
            'title' => 'Volunteer Engagement Coordinator',
            'description' => 'Recruit, train and support our volunteer network so that volunteers are well matched to programs and feel valued in their contribution to the community.',
            'salary' => '$72,000 – $80,000 per annum',
            'reports_to' => 'Operations Manager',
            'closing_date' => '2026-10-25',
            'responsibilities' => [
                'Recruit and onboard new volunteers across all programs.',
                'Plan and deliver regular volunteer induction and training sessions.',
                'Build volunteer rosters for community events and outreach.',
                'Check in with volunteers and resolve issues as they arise.',
                'Organise volunteer recognition and appreciation events.'
            ],
            'essential' => [
                'Experience coordinating volunteers, teams or community groups.',
                'Strong written and verbal communication skills.',
                'Current Working with Children Check.',
                'Confident using rostering or database software.'
            ],
            'preferable' => [
                'Qualification in community development, human resources or event management.',
                'Experience with volunteer-management platforms such as Better Impact.',
                'Availability for occasional evening and weekend events.'
            ]
        ],
        [
            'ref' => 'YOW338',
            'title' => 'Youth Outreach Worker',
            'description' => 'Work alongside young people aged 12–25 in their own communities, running outreach activities and mentoring that build confidence, connection and trust.',
            'salary' => '$65,000 – $70,000 per annum (pro rata for part-time)',
            'reports_to' => 'Youth Services Team Leader',
            'closing_date' => '2026-10-25',
            'responsibilities' => [
                'Deliver after-school, school-holiday and drop-in youth programs.',
                'Build trusted relationships with young people and their families.',
                'Recognise early signs of disengagement and respond appropriately.',
                'Refer young people to specialist support services when needed.',
                'Record session attendance and program outcomes.'
            ],
            'essential' => [
                'Certificate IV in Youth Work or a related qualification.',
                'Current Working with Children Check and National Police Check.',
                'Availability for after-school, evening and some weekend shifts.',
                'Current Victorian driver\'s licence.'
            ],
            'preferable' => [
                'Lived experience of, or a strong connection to, the local community.',
                'Experience facilitating group activities or workshops.',
                'Training in Youth Mental Health First Aid.'
            ]
        ]
    ];

    # Prepared statement: the values are sent separately from the SQL, so apostrophes in the text are safe #
    $insert_job = mysqli_prepare($dbconn, "INSERT INTO jobs
        (job_reference_number, title, short_description, salary, reports_to,
         responsibilities, essential_requirements, preferable_requirements, closing_date)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    mysqli_stmt_bind_param($insert_job, "sssssssss",
        $ref, $title, $description, $salary, $reports_to,
        $responsibilities, $essential, $preferable, $closing_date);

    foreach ($starting_jobs as $job) {
        $ref = $job['ref'];
        $title = $job['title'];
        $description = $job['description'];
        $salary = $job['salary'];
        $reports_to = $job['reports_to'];
        $responsibilities = implode("\n", $job['responsibilities']);   # one item per line #
        $essential = implode("\n", $job['essential']);
        $preferable = implode("\n", $job['preferable']);
        $closing_date = $job['closing_date'];                         # YYYY-MM-DD, the DATE format #
        mysqli_stmt_execute($insert_job);
    }

    mysqli_stmt_close($insert_job);
}

#              #
# eoi table    #
#              #

$create_eoi_query = "CREATE TABLE IF NOT EXISTS eoi (
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
) DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($dbconn, $create_eoi_query)) {
    die("<p>Unable to create the EOI table.</p>");
}

#              #
# users table  #
#              #

# Only a HASH of each password is stored, never the password itself. #
# failed_attempts and locked_until let login.php lock an account after too many wrong passwords #
$create_users_query = "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    failed_attempts INT NOT NULL DEFAULT 0,
    locked_until DATETIME NULL DEFAULT NULL
) DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($dbconn, $create_users_query)) {
    die("<p>Unable to create the users table.</p>");
}

# Older users tables have no lockout columns yet: add them #
add_column_if_missing($dbconn, "users", "failed_attempts", "INT NOT NULL DEFAULT 0");
add_column_if_missing($dbconn, "users", "locked_until", "DATETIME NULL DEFAULT NULL");

# Make sure the marker's admin account exists (username: admin, password: admin) #
$admin_username = "admin";
$find_admin = mysqli_prepare($dbconn, "SELECT user_id FROM users WHERE username = ?");
mysqli_stmt_bind_param($find_admin, "s", $admin_username);
mysqli_stmt_execute($find_admin);
mysqli_stmt_store_result($find_admin);
$admin_exists = mysqli_stmt_num_rows($find_admin) > 0;
mysqli_stmt_close($find_admin);

if (!$admin_exists) {
    # password_hash() adds a random salt and uses a slow algorithm, so the stored value is safe to keep #
    $admin_hash = password_hash("admin", PASSWORD_DEFAULT);
    $insert_admin = mysqli_prepare($dbconn, "INSERT INTO users (username, password_hash) VALUES (?, ?)");
    mysqli_stmt_bind_param($insert_admin, "ss", $admin_username, $admin_hash);
    mysqli_stmt_execute($insert_admin);
    mysqli_stmt_close($insert_admin);
}