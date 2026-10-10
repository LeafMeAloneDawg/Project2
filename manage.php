<!DOCTYPE HTML>
<?php 
    require_once("db_connect.php");

    if(isset($_GET['jobReferenceSearch']) && trim($_GET['jobReferenceSearch']) != ''){
        $jobRef = $_GET['jobReferenceSearch'];
        $sqlQuery = "SELECT * FROM eoi WHERE job_reference_number = '" . $jobRef ."'";
    }

    //Check if firstName OR lastName is set and not empty.
    elseif((isset($_GET['firstName']) || isset($_GET['lastName'])) && ((trim($_GET['firstName']) != '') || trim($_GET['lastName']) != '')){
        //Determines if first, last or both names are queried and non-empty.
        if(isset($_GET['firstName']) && (trim($_GET['firstName']) != '')){
            $first_name = trim($_GET['firstName']);
        }
        if(isset($_GET['lastName']) && (trim($_GET['lastName']) != '')){
            $last_name = trim($_GET['lastName']);
        }
        //If both first and last name are set, query both.
        if(isset($first_name) && isset($last_name)){
            $sqlQuery = "SELECT * FROM eoi WHERE first_name =" . "'" . $first_name .  "'" . " AND last_name =" . "'" . $last_name . "'"; 
        }
        //Else, find which is set and set appropriate query.
        elseif(isset($first_name)){
            $sqlQuery = "SELECT * FROM eoi WHERE first_name =" . "'" . $first_name .  "'";
        }
        elseif(isset($last_name)){
            $sqlQuery = "SELECT * FROM eoi WHERE last_name =" . "'" . $last_name .  "'";
        }
    }
    //If deleteJobReference field is set, deletes rows from SQL with matching Job Reference and redisplay table.
    elseif(isset($_POST["deleteJobReference"]) && $_POST["deleteJobReference"] != ''){
        $deleteJobReference = $_POST["deleteJobReference"];
        $sqlDelQuery = "DELETE FROM eoi WHERE job_reference_number = " . "'" . $deleteJobReference . "'";
        mysqli_query($dbconn, $sqlDelQuery);
        $sqlQuery = "SELECT * FROM eoi";
    }
    //If EOI ID is set, update status to selected value.
    elseif(isset($_POST['EOIUpdateStatusID']) && trim($_POST['EOIUpdateStatusID']) != ''){
        $EOI_ID = $_POST['EOIUpdateStatusID'];
        $newStatus = $_POST['status'];
        $sqlUpdateQuery = "UPDATE eoi SET status = " . "'" . $newStatus . "' WHERE EOInumber = " . "'" . $EOI_ID ."'";
        mysqli_query($dbconn, $sqlUpdateQuery);
        $sqlQuery = "SELECT * FROM eoi";
    }
    elseif(isset($_GET['sortField'])){
        $sortField = $_GET['sortField'];
        $sqlQuery = "SELECT * FROM eoi ORDER BY `$sortField` ASC";
    }
    //Default query - display all EOIs
    else{$sqlQuery = "SELECT * FROM eoi";}
    $result = mysqli_query($dbconn, $sqlQuery);
?>
<html>
    <head>
        <title>Management</title>
        <link rel="stylesheet" href="styles/styles.css">
        <style>label{font-weight: bold;}</style>
    </head>
    <body>
        <h1>Expression of interest managment</h1>

        <!-- Button to list all EOIs -->
        <h2>Search By:</h2>
        <form action="manage.php">
            <label>Show all EOIs:</label>
            <input type="submit" value="Show All">
        </form>

        <!-- Form to search by job reference -->
        <form method="GET" action="manage.php">
            <label for="jobReferenceSearch">Job reference:</label>
            <input type="text" name="jobReferenceSearch" id="jobReferenceSearch">
            <input type="submit" value="Search">
        </form>
        <!-- Form to search by first/last name or both -->
        <form>
            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" id="firstName">
            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" id="lastName">
            <input type="submit" value="Search">
        </form>

        <!-- Form to delete EOI given job reference -->
        <h2>Delete EOI by job reference</h2>
        <form method="POST" action="manage.php">
            <label for="deleteJobReference">Job Reference:</label>
            <input type="text" id="deleteJobReference" name="deleteJobReference">
            <input type="submit" value="Delete">
        </form>

        <!-- Form to change EOI status -->
        <h2>Change EOI status</h2>
        <form method="POST" action="manage.php">
            <label for="EOI_ID">EOI ID:</label>
            <input type="text" id="EOIUpdateStatusID" name="EOIUpdateStatusID" required>
            <b>Updated Status: </b>
            <!-- Change styling to style.css -->
            <span style="background-color:white">
            <label for="new">New:</label>
            <input type="radio" name="status" id="new" value="New" required>
            <label for="current">Current:</label>
            <input type="radio" name="status" id="current" value="Current">
            <label for="final">Final:</label>
            <input type="radio" name="status" id="final" value="Final">
            <input type="submit" value="Update Value">
            </span>
        </form>

        <!-- Form to select field to sort EOI table by -->
        <h2>Sort by field</h2>
        <form method="GET" action="manage.php">
            <label for="sortField">Sort entries by:</label>
            <select name="sortField" id="sortField">
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
            </select>
            <input type="submit" value="Sort list">
        </form>



        <?php 
            if($result){
                echo "<table> <th>EOI ID</th> <th>Job Ref</th> <th>First Name</th> <th>Last Name</th> <th>DOB</th> <th>Gender</th> <th>Address</th> <th>Email</th> <th>Phone Number</th> <th>Skills</th> <th>Other Skills</th> <th>Status</th>";
                while ($row = mysqli_fetch_assoc($result)){
                    echo "<tr>";
                    echo "<td>" . $row['EOInumber'] . "</td>";
                    echo "<td>" . $row['job_reference_number'] . "</td>";
                    echo "<td>" . $row['first_name'] . "</td>";
                    echo "<td>" . $row['last_name'] . "</td>";
                    echo "<td>" . $row['date_of_birth'] . "</td>";
                    echo "<td>" . $row['gender'] . "</td>";
                    echo "<td>" . $row['street_address'] . " " . $row['suburb'] . " " . $row['state'] . " " . $row['postcode'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>" . $row['skills'] . "</td>";
                    echo "<td>" . $row['other_skills'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "</tr>";
                }
            }
        ?>
    </body>
</html>