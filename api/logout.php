<?php

require_once("./include/util.php");
session_start();

session_unset();
session_destroy();
returnOK();

?>
