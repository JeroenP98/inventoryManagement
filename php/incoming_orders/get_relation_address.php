<?php

// create connection with database
require_once '../include/db_connect.php';

// Get the relation ID from the AJAX request
$relation_id = $_POST['relation_id'];

// Query the database for the relation address
$sql = "SELECT CONCAT(relations.street, ' ', relations.house_nr, ', ', relations.zip_code, ', ', relations.city, ', ', relations.country_code) AS address FROM relations WHERE id = $relation_id";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);
$address = $row['address'];

// Return the address as a string
echo $address;
?>