<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once '../include/loginCheck.php';
require_once '../include/db_connect.php';
require_once '../logging/controller_logfile.php';

class SearchesNotFoundController {
  public static function deleteSearchNotFound() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (isset($_POST['delete'])) {
        $searchInput = $_POST['search_input'];

        $stmt = $GLOBALS['connection']->prepare("DELETE FROM searches_not_found WHERE search_input = ?");
        $stmt->bind_param("s", $searchInput);

        if ($stmt->execute()) {
          // Return back to searches not found overview with a success message
          header("Location: ../searchesNotFound/GUI_searchesNotFound.php?action=delete&status=success&search=$searchInput");
          exit;
        } else {
          echo "Error deleting record: " . $stmt->error;
        }

        $stmt->close();
      }
    }
  }
}

if(isset($_POST["delete"])){
  SearchesNotFoundController::deleteSearchNotFound();
} else {
  header("Location: ../searchesNotFound/GUI_searchesNotFound.php");
  exit;
}
?>
