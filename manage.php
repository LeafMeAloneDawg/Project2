<!DOCTYPE HTML>
<?php 
    require_once("db_connect.php");
    if(isset($_GET['jobReference']) && trim($_GET['jobReference']) != ''){
        $jobRef = $_GET['jobReference'];
        $sqlQuery = "SELECT * FROM eoi WHERE job_reference_number = '" . $jobRef ."'";
    }
    else{$sqlQuery = "SELECT * FROM eoi";}
    $result = mysqli_query($dbconn, $sqlQuery);
?>
<html>
    <head>
        <title>Management</title>
        <link rel="stylesheet" href="styles/styles.css">
    </head>
    <body>
        <h1>Expression of interest managment</h1>
        <form method="GET" action="manage.php">
            <label for="jobReference"><b>Search by job reference:</b></label>
            <input type="text" name="jobReference" id="jobReference">
            <input type="submit">
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