<!-- navigation bar -->
<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<div class="nav_logo">
    <a href="index.php"><img src="images/grp_3.png" alt="MYSN Logo"></a>
</div>
<ul class="nav_links">
    <li><a href="index.php" <?php echo ($current_page == 'index.php') ? 'aria-current="page"' : ''; ?>>Home</a></li>
    <li><a href="jobs.php" <?php echo ($current_page == 'jobs.php') ? 'aria-current="page"' : ''; ?>>Jobs</a></li>
    <li><a href="apply.php" <?php echo ($current_page == 'apply.php') ? 'aria-current="page"' : ''; ?>>Apply</a></li>
    <li><a href="about.php" <?php echo ($current_page == 'about.php') ? 'aria-current="page"' : ''; ?>>About</a></li>
</ul>