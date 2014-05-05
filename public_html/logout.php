<!-- redirect to login page when user clicks on the logout option -->
<?php
    session_start();
    session_destroy();
    header("Location: http://holden.ischool.utexas.edu/~group2_trips/login.php");
?>