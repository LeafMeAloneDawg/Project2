<?php
//The wierd looking code below selects required values for DB access if using docker, and if ENV variables not found, defaults to those used for XAMPP.
$host = getenv('DB_HOST') ?: "127.0.0.1";
$user = getenv('DB_USER') ?: "root";
$pwd = getenv('DB_PASSWORD') ?: "";
$sql_db = "project2_db";

?>