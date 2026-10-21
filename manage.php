<?php require_once("auth_check.php"); ?>
<!DOCTYPE HTML>
<?php
require_once("db_connect.php");
require_once("db_setup.php");
//Check if searching by job reference.
if (isset($_GET['jobReferenceSearch']) && trim($_GET['jobReferenceSearch']) != '') {
    $jobRef = $_GET['jobReferenceSearch'];
    $stmt = $dbconn->prepare("SELECT * FROM eoi WHERE job_reference_number = ?");
    $stmt->bind_param("s", $jobRef);
}

//Check if firstName OR lastName is set and not empty.
elseif ((isset($_GET['firstName']) || isset($_GET['lastName'])) && ((trim($_GET['firstName']) != '') || trim($_GET['lastName']) != '')) {
    //Determines if first, last or both names are queried and non-empty.
    if (isset($_GET['firstName']) && (trim($_GET['firstName']) != '')) {
        $first_name = trim($_GET['firstName']);
    }
    if (isset($_GET['lastName']) && (trim($_GET['lastName']) != '')) {
        $last_name = trim($_GET['lastName']);
    }
    //If both first and last name are set, query both.
    if (isset($first_name) && isset($last_name)) {
        $stmt = $dbconn->prepare("SELECT * FROM eoi WHERE first_name = ? AND last_name = ?");
        $stmt->bind_param("ss", $first_name, $last_name);
    }
    //Else, find which is set and set appropriate query.
    elseif (isset($first_name)) {
        $stmt = $dbconn->prepare("SELECT * FROM eoi WHERE first_name = ?");
        $stmt->bind_param("s", $first_name);
    } elseif (isset($last_name)) {
        $stmt = $dbconn->prepare("SELECT * FROM eoi WHERE last_name = ?");
        $stmt->bind_param("s", $last_name);
    }
}
//If deleteJobReference field is set, deletes rows from SQL with matching Job Reference and redisplay table.
elseif (isset($_POST["deleteJobReference"]) && $_POST["deleteJobReference"] != '') {
    $deleteJobReference = $_POST["deleteJobReference"];

    $stmt = $dbconn->prepare("DELETE FROM eoi WHERE job_reference_number = ? ");
    $stmt->bind_param("s", $deleteJobReference);
    $stmt->execute();
    $stmt = $dbconn->prepare("SELECT * FROM eoi");

}
//If EOI ID is set, update status to selected value, ensuring the new status is a valid value. If invalid value, set $newStatus to null to avoid it being used later.
elseif (isset($_POST['EOIUpdateStatusID']) && trim($_POST['EOIUpdateStatusID']) != '') {
    $EOI_ID = $_POST['EOIUpdateStatusID'];
    $newStatus = $_POST['status'];
    $allowedStatus = array("New", "Current", "Final");
    if (in_array($newStatus, $allowedStatus)) {
        $stmt = $dbconn->prepare("UPDATE eoi SET status = ? WHERE EOInumber = ?");
        $stmt->bind_param("ss", $newStatus, $EOI_ID);
        $stmt->execute();
        $stmt = $dbconn->prepare("SELECT * FROM eoi");
    }
    else{
        $EOI_ID = null;
        $newStatus = null;
    die("Updated status is invalid");
    }


} elseif (isset($_GET['sortField'])) {
    $sortField = $_GET['sortField'];
    $sortOrder = $_GET['sortOrder'];
    //Array of allowed values to sort by.
    $allowedSortFields = array(
        "EOInumber",
        "job_reference_number",
        "first_name",
        "last_name",
        "date_of_birth",
        "gender",
        "street_address",
        "suburb",
        "state",
        "postcode",
        "email",
        "phone",
        "skills",
        "other_skills",
        "status"
    );

    if (in_array($sortField, $allowedSortFields) && ($sortOrder == "ASC" || $sortOrder == "DESC")) {
        $stmt = "SELECT * FROM eoi ORDER BY $sortField $sortOrder";
        $stmt = $dbconn->prepare($stmt);

    } 
    //If the sort field is not an allowed value, show all entries and set the values to null so they are not referenced later on.
    else {
        $stmt = $dbconn->prepare("SELECT * FROM eoi");
        $sortField = null;
        $sortOrder = null;
    }

}
//Default query - display all EOIs
else {
    $stmt = $dbconn->prepare("SELECT * FROM eoi");
}
if (!$stmt) {
    die("Database query preparation failed.");
}
$stmt->execute();

$result = $stmt->get_result();
?>
<html lang="en">

<head>
    <title>Management</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Mason Curlis">
    <link rel="stylesheet" href="styles/styles.css">
    <style>
        label {
            font-weight: bold;
        }
    </style>
</head>
<!-- Reused apply page CSS -->
<body class="apply-page">
    <?php include("header.inc"); ?>
    <nav>
        <?php include("nav.inc"); ?>
    </nav>
    <main id="managePage" class="apply-main">
        <div class="apply_intro_box">
            <h1>Job application management page</h1>
        </div>
        <!-- Login gate (line 1) and logout link added by Mohammad Aquib. GenAI acknowledgement:
             drafted with the assistance of GenAI, then reviewed and tested by Mohammad Aquib. -->
        <p><a href="logout.php">Log out (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></p>

        
        <div class="EOIManageForm">
            <div>
            <!-- Button to show all EOIs -->
            <fieldset>
                <legend>Show all EOIs:</legend>
                <form action="manage.php">
                    <label>Show all EOIs:</label>
                    <input type="submit" value="Show All">
                </form>
            </fieldset>
            <!-- Form to select field to sort EOI table by -->
            <fieldset>
                <legend>Sort By:</legend>
                <form method="GET" action="manage.php">
                    <label for="sortField">Sort entries by:</label>
                    <select size=1 name="sortField" id="sortField">
                        <option value="EOInumber">EOI ID</option>
                        <option value="job_reference_number">Job Reference Number</option>
                        <option value="first_name">First Name</option>
                        <option value="last_name">Last Name</option>
                        <option value="date_of_birth">DOB</option>
                        <option value="gender">Gender</option>
                        <option value="street_address">Street Address</option>
                        <option value="suburb">Suburb</option>
                        <option value="state">State</option>
                        <option value="postcode">Postcode</option>
                        <option value="email">Email Address</option>
                        <option value="phone">Phone Number</option>
                        <option value="skills">Skills</option>
                        <option value="other_skills">Other Skills</option>
                        <option value="status">Status</option>
                    </select>
                    <select size=1 name="sortOrder" id="sortOrder">
                        <option value="ASC">Ascending order</option>
                        <option value="DESC">Descending order</option>
                    </select>
                    <input type="submit" value="Sort list">
                </form>
            </fieldset>
            </div>
            

            
            <div>
            <!-- Form to delete EOI given job reference -->
            <fieldset>
                <legend>Delete EOI</legend>
                <form method="POST" action="manage.php">
                    <label for="deleteJobReference">Job Reference:</label>
                    <input type="text" id="deleteJobReference" name="deleteJobReference" required>
                    <input type="submit" value="Delete">
                </form>
            </fieldset>

            <!-- Form to change EOI status -->
            <fieldset>
                <legend>Change EOI status:</legend>
                <form method="POST" action="manage.php" class="flex-form">
                    <label for="EOIUpdateStatusID">EOI ID:</label>

                    <input type="text" id="EOIUpdateStatusID" name="EOIUpdateStatusID" required>
                    <br><label for="status">Status:</label>
                    <select size=1 name="status" id="status">
                        <option value="New">New</option>
                        <option value="Current">Current</option>
                        <option value="Final">Final</option>
                    </select>

                    <input type="submit" value="Update Value">
                </form>
            </fieldset>
            </div>
            <div>
             <!-- Form to search by job reference -->
            <fieldset class="managementFieldset">
                <legend>Search By:</legend>
                <div class="flex-form">
                    <form method="GET" action="manage.php" class="flex-form">
                        <label for="jobReferenceSearch">Job reference:</label>
                        <input type="text" name="jobReferenceSearch" id="jobReferenceSearch">
                        <input type="submit" value="Search">
                    </form>
                    <!-- Form to search by first/last name or both -->
                    <form class="flex-form">

                        <label for="firstName">First Name:</label>
                        <input type="text" name="firstName" id="firstName">
                        <br>
                        <label for="lastName">Last Name:</label>
                        <input type="text" name="lastName" id="lastName">
                        <input type="submit" value="Search">
                    </form>
                </div>
            </fieldset>
            </div>
        </div>
        

        <div class="content-background-and-border">
            <?php
            if(isset($deleteJobReference)){
                echo '<p>' .  mysqli_affected_rows($dbconn) . ' rows deleted.</p>';
            }
            elseif(isset($sortField) && isset($sortOrder)){

                echo '<p>Sorting by ' . $sortField . ' in '. $sortOrder . ' order.</p>';
            }
            elseif(isset($newStatus) && isset($EOI_ID)){
                echo '<p>Updated EOI #' . $EOI_ID . ' to '. $newStatus . '.</p>';
            }
            elseif(isset($first_name) && isset($last_name)){
                echo '<p>Searching for EOIs with applicant full name of ' . $first_name. ' '. $last_name . '.</p>';
            }
            elseif(isset($first_name)){
                echo '<p>Searching for EOIs with applicant first name of ' . $first_name. '.</p>';
            }
            elseif(isset($last_name)){
                echo '<p>Searching for EOIs with applicant last name of ' . $last_name . '.</p>';
            }
            elseif(isset($jobRef)){
                echo '<p>Searching for EOIs for job reference ' . $jobRef . '.</p>';
            }
            
            
            ?>
        </div>

        <?php

        
        if ($result) {
            echo "<table> <tr> <th>EOI ID</th> <th>Job Ref</th> <th>First Name</th> <th>Last Name</th> <th>DOB</th> <th>Gender</th> <th>Address</th> <th>Email</th> <th>Phone Number</th> <th>Skills</th> <th>Other Skills</th> <th>Status</th> </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['EOInumber']) . "</td>";
                echo "<td>" . htmlspecialchars($row['job_reference_number']) . "</td>";
                echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date_of_birth']) . "</td>";
                echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                echo "<td>" . htmlspecialchars($row['street_address']) . " " . $row['suburb'] . " " . $row['state'] . " " . $row['postcode'] . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                echo "<td>" . htmlspecialchars($row['skills']) . "</td>";
                echo "<td>" . htmlspecialchars($row['other_skills']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        mysqli_close($dbconn);
        ?>
    </main>
    <!-- Footer - consistent with the other pages -->
    <footer>
        <?php include("footer.inc"); ?>
    </footer>
</body>

</html>