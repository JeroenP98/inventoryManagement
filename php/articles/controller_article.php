<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap code-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="../../images/logo.png">
    <script src="../../js/formValidator.js"></script>
    <script src="../../js/tableSearch.js"></script>
    <script src="../../js/darkMode.js"></script>
    <title>Articles | GreenHome</title>
  </head>
<?php

//if session was started, continue it so it
session_start();

//check if user has logged in, in order to gain acces to the page. this disallows user to reach the page by entering the correct url
require_once '../include/loginCheck.php';

// create database connection with variables as parameters
require_once '../include/db_connect.php';

// add the php file for the action logging
require_once '../logging/controller_logfile.php';

// asses which action was used and call the corresponding class method
if(isset($_GET["action"])){
  $action = $_GET["action"];
  if($action === "add"){
    UserController::addArticle();
  } elseif($action === "edit"){
    UserController::editArticle();
  } elseif($action === "delete"){
    UserController::deleteArticle();
  }

} else {
  header("Location: ../users/GUI_users.php");
  exit;
};

class UserController {


public static function addArticle() {
    // Retrieve the connection from the global scope to be used in the function
    global $connection;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Store form data under variables which have been sanitized
        $name = htmlspecialchars($_POST["name"]);
        $description = htmlspecialchars($_POST["description"]);
        $purchase_price = htmlspecialchars(filter_var($_POST["purchase_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
        $selling_price = htmlspecialchars(filter_var($_POST["selling_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));

        // Check if all fields are filled
        if (empty($name) || empty($description) || empty($purchase_price) || empty($selling_price)) {
            $error_message = "All fields are required";
            echo $error_message;
        }

        if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['article_image'];

            // Retrieve the file information
            $file_name = $file['name'];
            $file_tmp = $file['tmp_name'];
            $file_size = $file['size'];
            $file_type = $file['type'];

            // Get the file extension
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Define the allowed file extensions
            $allowed_extensions = array("jpeg", "jpg", "png");

            // Check if the file extension is allowed
            if (in_array($file_ext, $allowed_extensions)) {
                // Define the maximum file size (2MB)
                $max_file_size = 2097152;

                // Check if the file size exceeds the maximum allowed size
                if ($file_size <= $max_file_size) {
                    // Read the file content and escape it for SQL
                    $file_content = mysqli_real_escape_string($connection, file_get_contents($file_tmp));

                    // Determine the image MIME type
                    $image_mime = $file_type;

                    // Prepare the SQL query with parameters for the file data and MIME type
                    $sql = "INSERT INTO articles (`name`, `description`, `purchase_price`, `selling_price`, `image_data`, `image_mime`) 
                            VALUES ('$name', '$description', '$purchase_price', '$selling_price', '$file_content', '$image_mime')";

                    try {
                        // Execute the SQL query
                        mysqli_query($connection, $sql);

                        // Add logfile record
                        $action = "add";
                        $object_type = "Article";
                        LogfileHandler::addLogfileRecord($action, $object_type, $name, "new article");
                    } catch (mysqli_sql_exception $e) {
                        $error_message = "Invalid query: " . $e;
                        echo $error_message;
                        exit;
                    }

                    // Return back to article overview after posting the record
                    header("location: GUI_articles.php?action=add&status=succes&article=$name");
                    exit;
                } else {
                    $error_message = "File size must be less than or equal to 2 MB";
                    echo $error_message;
                }
              } else {
                $error_message = "Extension not allowed, please choose a JPEG or PNG file.";
                echo $error_message;
                exit;
            }
        } else {
            // No file upload, continue without saving the image
            // Prepare the SQL query without image data and MIME type
            $sql = "INSERT INTO articles (`name`, `description`, `purchase_price`, `selling_price`) 
                    VALUES ('$name', '$description', '$purchase_price', '$selling_price')";

            try {
                // Execute the SQL query
                mysqli_query($connection, $sql);

                // Add logfile record
                $action = "add";
                $object_type = "Article";
                LogfileHandler::addLogfileRecord($action, $object_type, $name, "new article");
            } catch (mysqli_sql_exception $e) {
                $error_message = "Invalid query: " . $e;
                echo $error_message;
                exit;
            }

            // Return back to article overview after posting the record
            header("location: GUI_articles.php?action=add&status=succes&article=$name");
            exit;
        }
    }
}

  

public static function editArticle() {
  // Retrieve the connection from the global scope to be used in the function
  global $connection;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Store form data under variables which have been sanitized
      $id = $_POST["id"];
      $name = $connection->escape_string($_POST["name"]);
      $description = $connection->escape_string($_POST["description"]);
      $purchase_price = htmlspecialchars(filter_var($_POST["purchase_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
      $selling_price = htmlspecialchars(filter_var($_POST["selling_price"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
      $is_active = htmlspecialchars(filter_var($_POST["is_active"], FILTER_SANITIZE_NUMBER_INT));

      try {
          // Prepare the SQL query for updating the record
          $sql = "UPDATE `articles` SET `name`='$name', `description`='$description', `purchase_price`=$purchase_price, `selling_price`=$selling_price, `is_active`=$is_active";

          // Check if file upload exists and is successful
          if (isset($_FILES['article_image']) && $_FILES['article_image']['error'] === UPLOAD_ERR_OK) {
              // Retrieve the file information
              $file_tmp = $_FILES['article_image']['tmp_name'];
              $file_size = $_FILES['article_image']['size'];
              $file_type = $_FILES['article_image']['type'];

              // Read the file content and escape it for SQL
              $file_content = file_get_contents($file_tmp);
              $escaped_file_content = mysqli_real_escape_string($connection, $file_content);

              // Check if file content is empty
              if (empty($file_content)) {
                  echo 'File content is empty.';
                  exit;
              }

              // Update the SQL query with image data and MIME type
              $sql .= ", `image_data`='$escaped_file_content', `image_mime`='$file_type'";
          } else if ($_FILES['article_image']['error'] === UPLOAD_ERR_NO_FILE) {
              // No file was uploaded, we just continue without altering image data
          } else {
              // Unknown error with file upload
              echo 'Error with file upload: ' . $_FILES['article_image']['error'];
              exit;
          }

          // Append WHERE clause regardless of image upload
          $sql .= " WHERE `id` = $id";

          // Execute the SQL query
          if (!mysqli_query($connection, $sql)) {
              // Print any SQL errors
              echo 'SQL Error: ' . mysqli_error($connection);
              exit;
          }

          // Add logfile record
          $action = "edit";
          $object_type = "Article";
          LogfileHandler::addLogfileRecord($action, $object_type, $name, "edit article");

          // Return back to the article overview after updating the record
          header("location: GUI_articles.php?action=edit&status=succes&article=$name");
          exit;
      } catch (mysqli_sql_exception $e) {
          $error_message = "Invalid query: " . $e;
          echo $error_message;
          exit;
      }
  }
}



  public static function deleteArticle(){
    //retrieve the connection from the global scope to be used in the function
    global $connection;

    if(isset($_GET["id"])){

    // retrieve the ID from selected DB record from article overview
    $id = $_GET["id"];
  
    // declare variables for logging purposes before the article is deleted
    $stmt = "SELECT name FROM articles WHERE id=$id";
    $result = $connection->query($stmt);
    $row = $result->fetch_assoc();
    $name = $row["name"];
  

    //prepare query to delete record
    $sql = "DELETE FROM articles WHERE id=$id";

    // excecute sql query
    $connection->query($sql);

    // add logfile record
    $action = "delete";
    $object_type = "Article";
    LogfileHandler::addLogfileRecord($action, $object_type, $name, "delete article");

    // return back to article overview
    header("location: GUI_articles.php?action=delete&status=succes&article=$name");
    exit;
  
    }
  }
}

if ($_GET["action"] == "import") {
  if (isset($_FILES["csvFile"])) {
    $csvFile = $_FILES["csvFile"]["tmp_name"];
    if (($handle = fopen($csvFile, "r")) !== FALSE) {
      fgetcsv($handle);  // Skip the first row (header)

      while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $article_id = isset($data[0]) ? intval($data[0]) : 0;  // added this line
        $name = isset($data[1]) ? $connection->real_escape_string($data[1]) : '';
        $description = isset($data[2]) ? $connection->real_escape_string($data[2]) : '';
        $purchase_price = isset($data[3]) ? floatval(str_replace(",", ".", $data[3])) : 0.0;
        $selling_price = isset($data[4]) ? floatval(str_replace(",", ".", $data[4])) : 0.0;
        $is_active = isset($data[5]) ? boolval($data[5]) : true;

        // Check if record exists
        $check = $connection->prepare("SELECT * FROM articles WHERE id = ?");
        $check->bind_param("i", $article_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
          // Record exists, update it
          $stmt = $connection->prepare("UPDATE articles SET name = ?, description = ?, purchase_price = ?, selling_price = ?, is_active = ? WHERE id = ?");
          $stmt->bind_param("ssddii", $name, $description, $purchase_price, $selling_price, $is_active, $article_id);
        } else {
          // Record doesn't exist, insert it
          $stmt = $connection->prepare("INSERT INTO articles (id, name, description, purchase_price, selling_price, is_active) VALUES (?, ?, ?, ?, ?, ?)");
          $stmt->bind_param("issddi", $article_id, $name, $description, $purchase_price, $selling_price, $is_active);
        }

        if ($stmt->execute()) {
          // Insertion or update successful
        } else {
          // Insertion or update failed
        }
      }

      fclose($handle);
      header("Location: GUI_articles.php?action=import&status=success");
      exit;
    }
  }
}




?>
</html>