<?php
// page for logging out
session_start();

// safely get rid of all user data and destroy current session info.
session_unset();
session_destroy();

header("Location: home.php");
exit;
?>
