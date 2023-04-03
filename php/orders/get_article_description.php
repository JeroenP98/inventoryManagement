<?php

// create connection with database
require_once '../include/db_connect.php';

// Get the article ID from the AJAX request
$article_id = $_POST['article_id'];

// Query the database for the article description
$sql = "SELECT description FROM articles WHERE id = $article_id";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
$description = $row['description'];

// Return the description as a string
echo $description;
?>