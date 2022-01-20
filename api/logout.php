<?php

// Import shared functions and open PHP session
require_once("./include/util.php");
session_start();

// Destroy session to remove logged-in user data
session_unset();
session_destroy();

// Return empty OK indicating success
returnOK();

?>
