<?php
/*
filename: db_setup.php
author: Mohammad Aquib (jobs and users tables)
contributors: Xander Rode-Bramanis (eoi table), Ryan M (members and member_contributions tables)
created: 13/10/2026
last modified: 23/10/2026
description: Shared database setup. Creates the jobs, eoi, users, members and
             member_contributions tables if they do not exist yet, and fills jobs, users,
             members and member_contributions with starting data. Included by search.php
             (for jobs.php), apply.php, process_eoi.php, login.php, manage.php and about.php.

GenAI acknowledgement: the jobs and users sections of this file (prepared statements,
password hashing) were drafted with the assistance of GenAI, then reviewed, tested and
edited by Mohammad Aquib.
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

#                #
# members table  #
#                #
$create_members_query = "CREATE TABLE IF NOT EXISTS members (
    student_id VARCHAR(10) NOT NULL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    fun_fact_1 TEXT NOT NULL,
    fun_fact_2 TEXT NOT NULL,
    fun_fact_3 TEXT NOT NULL,
    quote TEXT NOT NULL,
    quote_translated TEXT NOT NULL
) DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($dbconn, $create_members_query)) {
    die("<p>Unable to create the members table.</p>");
}

$count_members = mysqli_query($dbconn, "SELECT COUNT(*) AS total FROM members");
$row_members = mysqli_fetch_assoc($count_members);

if ($row_members['total'] == 0) {
    $starting_members = [
        ['103839016', 'Aquib', 'Plays guitar.', 'Enjoys live rock/metal concerts.', 'Loves to collect band official merchandise.', 'কখনো হাল ছাড়বে না', 'Never give up '],    
        ['105641802', 'Mason', 'My bedroom was a 1940s double-decker bus as a teen.', 'I live with my best friend from year 7.', 'I learnt to weld to fix my the love of my life (A 1984 Celica), blew her up three times and then sold it.', 'पेड़ मेरे पसंदीदा जानवर हैं।', 'Trees are my favorite animal.'],
        ['106893099', 'Ryan', 'Has a pet dog named Fuji.', 'Has an interest in old Toyotas.', 'Enjoys CAD design and 3d printing.', '胸を張って生きろ。己の弱さや不甲斐なさにどれだけ打ちのめされようと、心を燃やせ。', 'Go ahead and live with your head held high! No matter how devastated you may be by your own weakness or uselessness, set your heart ablaze!'],
        ['103990515', 'Xander', 'Lived in Thailand for a year.', 'Collects physical media.', 'Enjoys surfing.', 'ผมรักแม่ของผม', 'I love my mum']
    ];

    $insert_member = mysqli_prepare($dbconn, "INSERT INTO members (student_id, name, fun_fact_1, fun_fact_2, fun_fact_3, quote, quote_translated) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($insert_member, "sssssss", $m_id, $m_name, $m_f1, $m_f2, $m_f3, $m_q, $m_qt);

    foreach ($starting_members as $m) {
        list($m_id, $m_name, $m_f1, $m_f2, $m_f3, $m_q, $m_qt) = $m;
        mysqli_stmt_execute($insert_member);
    }
    mysqli_stmt_close($insert_member);
}


#                            #
# member_contributions table #
#                            #
$create_contrib_table = "CREATE TABLE IF NOT EXISTS member_contributions (
    contribution_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(10) NOT NULL,
    project_part VARCHAR(25) NOT NULL,
    task_description TEXT NOT NULL,
    CONSTRAINT foreign_key_member FOREIGN KEY (student_id) REFERENCES members (student_id) ON DELETE CASCADE ON UPDATE CASCADE
) DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($dbconn, $create_contrib_table)) {
    die("<p>Unable to create the member_contributions table.</p>");
}

$count_contrib = mysqli_query($dbconn, "SELECT COUNT(*) AS total FROM member_contributions");
$row_contrib = mysqli_fetch_assoc($count_contrib);

if ($row_contrib['total'] == 0) {
    $starting_contribs = [
        ['103839016', '1', 'Job Page Html'],
        ['103839016', '1', 'Job Page CSS'],
        ['103839016', '1', 'Shared responsibility on Jira'],
        ['103839016', '1 & 2', 'Shared responsibility making regular commits to Github'],
        ['103839016', '1 & 2', 'Shared responsibility joining group meetings, in class and online'],
        ['103839016', '1 & 2', 'Shared responsibility communicating in timely manner to group chats'],
        ['103839016', '1', 'Shared responsibility combining CSS sheets into one style.css'],
        ['103839016', '2', 'Create jobs table'],
        ['103839016', '2', 'Created login page'],
        ['103839016', '2', 'Created search.php'],
        ['103839016', '2', 'Populate jobs table from database'],
        ['103839016', '2', 'Implement jobs table creation if not exit'],
        ['103839016', '2', 'Implemented secure session setup and login'],
        ['103839016', '2', 'Implemented redirection of logged in users'],
        ['103839016', '2', 'Implemented login attempt limit'],
        ['103839016', '2', 'Implemented login link in footer'],
        ['103839016', '2', 'Implemented search.php into jobs.php'],

        ['105641802', '1', 'Home Page Html'],
        ['105641802', '1', 'Home Page CSS'],
        ['105641802', '1', 'Shared responsibility on Jira'],
        ['105641802', '1 & 2', 'Shared responsibility making regular commits to Github'],
        ['105641802', '1 & 2', 'Shared responsibility joining group meetings, in class and online'],
        ['105641802', '1 & 2', 'Shared responsibility communicating in timely manner to group chats'],
        ['105641802', '1', 'Shared responsibility combining CSS sheets into one style.css'],
        ['105641802', '2', 'Duplicated project from part 1'],
        ['105641802', '2', 'Created db_connect.php'],
        ['105641802', '2', 'Created manage.php'],
        ['105641802', '2', 'Implement manage.php sorting functions'],
        ['105641802', '2', 'Implemented manage.php searching functions'],
        ['105641802', '2', 'Styled and formatted manage.php page'],

        ['106893099', '1', 'About Page Html'],
        ['106893099', '1', 'About Page CSS'],
        ['106893099', '1', 'Shared responsibility on Jira'],
        ['106893099', '1 & 2', 'Shared responsibility making regular commits to Github'],
        ['106893099', '1 & 2', 'Shared responsibility joining group meetings, in class and online'],
        ['106893099', '1 & 2', 'Shared responsibility communicating in timely manner to group chats'],
        ['106893099', '1', 'Shared responsibility combining CSS sheets into one style.css'],
        ['106893099', '2', 'Converted pages to .php rather than .html'],
        ['106893099', '2', 'Created members table'],
        ['106893099', '2', 'Created member_contribution table'],
        ['106893099', '2', 'Cleaned up header.inc, nav.inc and footer.inc files'],
        ['106893099', '2', 'Fixed skip link for all pages'],
        ['106893099', '2', 'Fixed photo caption size in about.php'],
        ['106893099', '2', 'Fixed navigation bar underline for current page'],

        ['103990515', '1', 'Apply Page Html'],
        ['103990515', '1', 'Apply Page CSS'],
        ['103990515', '1', 'Shared responsibility on Jira'],
        ['103990515', '1 & 2', 'Shared responsibility making regular commits to Github'],
        ['103990515', '1 & 2', 'Shared responsibility joining group meetings, in class and online'],
        ['103990515', '1 & 2', 'Shared responsibility communicating in timely manner to group chats'],
        ['103990515', '1', 'Shared responsibility combining CSS sheets into one style.css'],
        ['103990515', '2', 'Created settings.php'],
        ['103990515', '2', 'Created EOI.php'],
        ['103990515', '2', 'Created EOI table'],
        ['103990515', '2', 'Implemented form validation error handling'],
        ['103990515', '2', 'Sanitized form input data to ensure secure database insertion and prevent SQL injection']
    ];

    $insert_contrib = mysqli_prepare($dbconn, "INSERT INTO member_contributions (student_id, project_part, task_description) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($insert_contrib, "sss", $c_sid, $c_part, $c_desc);

    foreach ($starting_contribs as $c) {
        $c_sid = $c[0];
        $c_part = $c[1];
        $c_desc = $c[2];
        mysqli_stmt_execute($insert_contrib);
    }
    mysqli_stmt_close($insert_contrib);
}
?>