<?php

// check if the user id has been set to session variable (which is done in the login page) in order to gain or deny acces to the page
if(!isset($_SESSION["user_id"])){
  header("Location: ../inventorymanagement/php/users/GUI_login.php?failed=true");
  exit;
}

?>