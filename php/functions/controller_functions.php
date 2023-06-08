<?php
// If session was started, continue it
session_start();

// Check if user has logged in to gain access to the page
require_once '../include/loginCheck.php';

// Create database connection with variables as parameters
require_once '../include/db_connect.php';

// Add the PHP file for the action logging
require_once '../logging/controller_logfile.php';

// Assess which action was used and call the corresponding class method
if (isset($_GET["action"])) {
  $action = $_GET["action"];
  if ($action === "add") {
    FunctionController::addFunction();
  } elseif ($action === "edit") {
    FunctionController::editFunction();
  } elseif ($action === "delete") {
    FunctionController::deleteFunction();
  }
} else {
  header("Location: GUI_functions.php");
  exit;
}

class FunctionController
{
  public static function addFunction()
  {
    // Retrieve the connection from the global scope to be used in the function
    global $connection;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Store form data under variables which have been sanitized
      $name = htmlspecialchars($_POST["name"]);

      // Check if the function name field is filled
      if (empty($name)) {
        $error_message = "Function name is required";
        echo $error_message;
      } else {
        try {
          // Prepare SQL query to insert data into the table
          $sql = "INSERT INTO `functions` (`name`) VALUES ('$name')";

          // Execute SQL query
          if ($connection->query($sql) === TRUE) {
            // Add logfile record
            $action = "add";
            $object_type = "Function";
            LogfileHandler::addLogfileRecord($action, $object_type, $name, "new Function");

            // Redirect back to the functions page with success message
            header("Location: GUI_functions.php?action=add&status=success&function=$name");
            exit;
          } else {
            // Handle query execution error
            echo "Error: " . $sql . "<br>" . $connection->error;
          }
        } catch (mysqli_sql_exception $e) {
          $error_message = "Invalid query: " . $e;
          echo $error_message;
        }
      }
    }
  }

  public static function editFunction()
  {
      global $connection;
  
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Store form data under variables which have been sanitized
          $new_name = htmlspecialchars($_POST["new_name"]);
          $current_name = $_GET["name"];
  
          // Check if the row is used in the accessibility page
          $sql_check_usage = "SELECT * FROM `accessibilities` WHERE `function_name`='$current_name'";
          $result_check_usage = $connection->query($sql_check_usage);
  
          if ($result_check_usage->num_rows > 0) {
              // Redirect back to the GUI_functionEditor.php page with error status
              header("Location: GUI_functionEditor.php?name=$current_name&status=error");
              exit;
          }
  
          // Prepare SQL query to update the record
          $sql = "UPDATE `functions` SET `name`='$new_name' WHERE `name`='$current_name'";
  
          // Execute SQL query
          if ($connection->query($sql) === TRUE) {
              // Add logfile record
              $action = "edit";
              $object_type = "Function";
              LogfileHandler::addLogfileRecord($action, $object_type, $new_name, "edit Function");
  
              // Redirect back to the functions page with success message
              header("Location: GUI_functions.php?action=edit&status=success&function=$new_name");
              exit;
          } else {
              // Handle query execution error
              echo "Error: " . $sql . "<br>" . $connection->error;
          }
      }
  }
  


  public static function deleteFunction()
{
    // Retrieve the connection from the global scope to be used in the function
    global $connection;

    if (isset($_GET["name"])) {
        // Retrieve the function name from the query parameters
        $function_name = $_GET["name"];

        try {
            // Prepare SQL query to delete the record
            $sql = "DELETE FROM `functions` WHERE `name` = ?";

            // Prepare the delete statement
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("s", $function_name);

            // Execute the delete statement
            if ($stmt->execute()) {
                // Add logfile record
                $action = "delete";
                $object_type = "Function";
                LogfileHandler::addLogfileRecord($action, $object_type, $function_name, "delete Function");

                // Redirect back to the functions page with success message
                header("Location: GUI_functions.php?action=delete&status=success&function=$function_name");
                exit();
            } else {
                // Handle query execution error
                echo "Error deleting function: " . $stmt->error;
            }
        } catch (mysqli_sql_exception $e) {
            // Handle the exception and display the error message
            $error_message = "Error deleting function: " . $e->getMessage();
            echo $error_message;
        }
    } else {
        // Redirect back to the functions page with error message
        header("Location: GUI_functions.php?action=delete&status=error");
        exit();
    }
}
}