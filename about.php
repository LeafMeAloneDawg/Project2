<?php
// Database Connection 
require_once "settings.php"; 
$dbconn = @mysqli_connect($host, $user, $password, $sql_db);

if (!$dbconn) {
    die("<p>Database connection failed: " . mysqli_connect_error() . "</p>");
}
// Database Connection
 require_once("db_connect.php");
// Check if table exists already and create it if not 
$create_table_query = "CREATE TABLE IF NOT EXISTS members (
  `student_id` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `fun_fact_1` text NOT NULL,
  `fun_fact_2` text NOT NULL,
  `fun_fact_3` text NOT NULL,
  `quote` text NOT NULL,
  `quote_translated` text NOT NULL,
  PRIMARY KEY (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

mysqli_query($dbconn,$create_table_query);

$create_contrib_table = "CREATE TABLE IF NOT EXISTS `member_contributions` (
  `contribution_id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `project_part` varchar(25) NOT NULL,
  `task_description` text NOT NULL,
  PRIMARY KEY (`contribution_id`),
  KEY `foreign_key_member` (`student_id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

mysqli_query($dbconn,$create_contrib_table);

// Fetch members data sorted by name
$query = "SELECT * FROM members ORDER BY name ASC;";
$result = mysqli_query($dbconn,$query);
?>

<!DOCTYPE html>
<html lang="en">

<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Shares more information about our group and each member.">
    <meta name="keywords" content="About Group, Group Name, Group Class, Group Photo">
    <meta name="author" content="Ryan M">
    <title>About Us</title>
    <link rel="stylesheet" href="styles/styles.css">
<style>
    .about_h1{
        text-align: center; 
        border-radius: 25px; 
        border: 1px solid #3b82f6; 
        margin-left: 27%;
        margin-right: 27%;
    }
</style>
</head> 

<body>
    <!-- header -->
    <?php include ("header.inc"); ?>
<!-- navigation bar -->
<nav>
    <?php include ("nav.inc"); ?>
</nav>

<main class="main_about" id="main-content" tabindex="-1">

<h1 class="about_h1" style="color: #09427e; ">About Us!</h1>

    <!-- group photo / banner with text on top -->
<figure class="banner_text">
    <img src="styles/images/group_photo.png" id="group_banner" alt="Team Group Photo" class="group_banner">
    <figcaption class="text_overlay">Xander, Aquib, Ryan, Mason</figcaption>
</figure>

<!-- little info about company -->
<p class="about_intro">
    <strong>"No young person has to face life alone."</strong>
    <br>
    Meet the team behind Melbourne Youth Support! 
</p>
<section class="about_layout">
    <h2>Course Details</h2>
        <!-- unordered list of course details -->
        <ul class="course_details">
            <li>Campus
                <ul>
                    <li>Hawthorn</li>
                </ul>
            </li>
            <li>Web Technology Project - COS10026
                <ul>
                    <li>Thursday 12:30pm - 2:30pm Class</li>
                    <li>Tutor: Nick</li>
                    <li>Group: 3</li>
                </ul>
            </li>
            <li>Members
                <ul>
                    <?php 
                    // Dynamic member list
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($member = mysqli_fetch_assoc($result)) {
                            echo "<li>" . htmlspecialchars($member['name']) . " - StudentID: " . htmlspecialchars($member['student_id']) . "</li>";
                        }
                        // Reset cursor to loop over data again for the tables
                        mysqli_data_seek($result, 0); 
                    }
                    ?>
                </ul>
            </li>  
        </ul>
</section>

<!-- member contributions presented using a definition list -->
<section class="about_layout">
    <h2> Group Contributions</h2>
<!-- definition list of group contributions -->
    <dl class="group_contributions">
        <?php
        // Query contributions joined with members 
        $contrib_query = "SELECT m.name, c.project_part, c.task_description 
                          FROM member_contributions c 
                          JOIN members m ON c.student_id = m.student_id 
                          ORDER BY m.name ASC, c.project_part ASC, c.contribution_id ASC;";
        
        $contrib_result = mysqli_query($dbconn, $contrib_query);

        if ($contrib_result && mysqli_num_rows($contrib_result) > 0) {
            $current_name = ""; // Tracks the name from the previous row

            while ($row = mysqli_fetch_assoc($contrib_result)) {
                // If the name changes, close the previous person's tags and start a new one
                if ($row['name'] !== $current_name) {
                    if ($current_name !== "") {
                        echo "</ol></dd>"; // Close previous list/definition block
                    }
                    $current_name = $row['name'];
                    echo "<dt><strong>" . htmlspecialchars($current_name) . "</strong></dt>";
                    echo "<dd><ol>";
                }
                
                // Print each task with its project part clearly visible for the marker
                echo "<li><strong>Part: " . htmlspecialchars($row['project_part']) . ":</strong> " . htmlspecialchars($row['task_description']) . "</li>";
            }
    
            // Close the tags for the very last person after the loop finishes
            if ($current_name !== "") {
                echo "</ol></dd>";
            }
        } else {
            echo "<dt>No contributions found.</dt>";
        }
        ?>
    </dl> 
</section>
  
<section class="about_layout">
    <h2>Group Members</h2>
<!-- table of members and fun facts -->
    <table class="table_layout">
        <caption>Group Member Fun Facts</caption>
        <thead>
            <tr>
            <th>Name</th>
            <th>Fun Fact #1</th>
            <th>Fun Fact #2</th>
            <th>Fun Fact #3</th>
            </tr>
        </thead>
        <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fun_fact_1']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fun_fact_2']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fun_fact_3']) . "</td>";
                        echo "</tr>";
                    }
                    // Reset internal pointer so we can re-read rows for table 2
                    mysqli_data_seek($result, 0); 
                } else {
                    echo "<tr><td colspan='4'>No member records found.</td></tr>";
                }
                ?>
            </tbody>
    </table>
    <br>
    <!-- table of member quotes -->
    <table class="table_layout">
        <caption>Our Favourite Quotes</caption>
        <thead>
            <tr>
                <th>Member</th>
                <th>Quote</th>
                <th>Translation</th>
            </tr>
        </thead>
        <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quote']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['quote_translated']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    // Safety net for no records found
                    echo "<tr><td colspan='3'>No quotes found.</td></tr>";
                }
                // Close DB connection after queries are finished
                mysqli_close($dbconn);
                ?>
            </tbody>
    </table>
</section>
</main>

<!-- footer -->
<footer>
    <?php include 'footer.inc'; ?>
</footer>
</body>
</html>