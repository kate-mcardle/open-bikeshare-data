<!-- executes query to add new rider to riders table -->
<?php
// set permission level
$page_type = "admin";
include("protected.php");

// check to see if rider level variables are set
if (isset($_GET["rider_id"]) && isset($_GET["rider_type"]) && isset($_GET["gender"]) && isset($_GET["birth_year"])) {
  include "../mysql_connect.php";
  // extract rider information
  $rider_id = $_GET["rider_id"];
  $rider_type = $_GET["rider_type"];
  $gender = $_GET["gender"];
  $birth_year = $_GET["birth_year"];
  // prep query to add a rider to the riders table
  $query = "INSERT into riders(id,rider_type,gender,birth_year) ";
  $query .= "VALUES (?,?,?,?)";

  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "isss", $rider_id, $rider_type, $gender, $birth_year);
  // execute insert
  $did_execute = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  // assess the results of the add
  $msg = "";
  if ($did_execute) {
    $msg = "You successfully added rider ". $rider_id . "!";
  } else {
    $msg = "There was an error adding that trip. Please try again.";
  }
  // redirect the message ot the admin facing result page
  $_SESSION["msg"] = $msg;
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
  exit;
}
// if the variables weren't set, redirect to the admin home page so the admin can start another session
else {
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php");
  exit;
}
?>