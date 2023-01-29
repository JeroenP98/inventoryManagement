<?php

//Create the code during login proces which checks if a "greenhome" database exists. it needs that database in order to fetch login details. if the database already exists, the file will do nothing.

//If the database does not exists, it will create one and run the sql commands in from the sql file in the "sql" directory. 

// create database connection with variables as parameters
$servername = "localhost";
$username = "";
$password = "";
$database = "greenhome";
$connection = new mysqli($servername, $username, $password, $database);


// check if the database exists
$result = mysqli_query($connection, "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'greenhome' ");

//if the num of rows that the query returns is 0, then it is true the database does not exist
if(mysqli_num_rows($result) == 0) {
    // create the database if it doesn't exist
    mysqli_query($connection, "CREATE DATABASE greenhome");
    // select database to specify where to execute sql queries
    mysqli_select_db($connection, $database);
    // select the .sql file to run
    $file_path = '../../sql/greenhome.sql';
    //converts the sql file to a string
    $queries = file_get_contents($file_path);
    // executes the sql file
    if(mysqli_multi_query($connection, $queries)) {
      // Continue processing the result sets returned by the executed queries
        do {
            if (mysqli_more_results($connection)) {
              // Move to the next result set
                if (!mysqli_next_result($connection)) {
                  // If there are no more result sets, break out of the loop
                    break;
                }
            }
             // Save the current result set
            $result = mysqli_store_result($connection);
            if($result)
            // Free up the memory associated with the result set
                mysqli_free_result($result);
        } while (mysqli_more_results($connection));
    } else {
      // If an error occurred during the execution of the SQL commands
        echo "Error: " . mysqli_error($connection);
}
}
// Close the database connection
$connection->close();

?>
