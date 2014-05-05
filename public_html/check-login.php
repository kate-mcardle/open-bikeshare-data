<!-- queries the database to check that login credentials are valid -->
<?php
// check to see if the username and password have been submitted
if (isset($_GET["submitted"])) {
  $user = $_GET["username"];
  $pwd = $_GET["password"];

  # Verify user and determine access level:
  include("../mysql_connect.php");
  // query that checks if username and password are in the database and gets the access level for a user
  $query = "SELECT admins.username, admins.access_level ";
  $query .= "FROM admins ";
  $query .= "WHERE admins.username = ? AND admins.password = ?";

  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "ss", $user, $pwd);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $username, $level);
  // base link that we will concatenate webpage paths to based on their level of access
  $redirect = "Location: http://holden.ischool.utexas.edu/~group2_trips/";

  while (mysqli_stmt_fetch($stmt)) {
    session_start();
    $_SESSION["user"] = $username;
    // if user is a superuser, redirect to super user page
    if ($level == "superuser") {
      $_SESSION["access_level"] = "superuser";
      $redirect .= "admin-su.php";
    }
    // if user is an admin, redirect to admin page
    elseif ($level == "admin") {
      $_SESSION["access_level"] = "admin";
      $redirect .= "admin.php";
    }
    // otherwise the username/password combination is not in the table so redirect to login failed site
    else {
      session_destroy();
      $redirect .= "login.php?login_failed=true";
    }
  }
  mysqli_stmt_close($stmt);
  // redirect to login failed site
  if (!isset($_SESSION["user"])) {
      session_destroy();
      $redirect .= "login.php?login_failed=true";   
  }
  header($redirect);

}
else { # control should only arrive here if someone manually goes to login.php
  header( 'Location: http://holden.ischool.utexas.edu/~group2_trips/login.php' );
}
?>