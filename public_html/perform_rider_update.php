
<?php
// Set permission level
$page_type = "admin";
include("protected.php");
// if there is an old user id, it means we need to update the riders table
if (isset($_GET["old_user_id"])) {
  include "../mysql_connect.php";
  // extract the updated features from the $_GET array and feed them in as parameters to the query
  $old_user_id = $_GET["old_user_id"];
  $new_user_id = $_GET["new_user_id"];
  $user_type = $_GET["user_type"];
  $gender = $_GET["gender"];
  $birth_year = $_GET["birth_year"];
  // query to update the rider features
  $query = "UPDATE riders ";
  $query .= "SET riders.id = ?, riders.rider_type = ?, riders.gender = ?, riders.birth_year = ? ";
  $query .= "WHERE riders.id = ?";
  // prepare and execute query
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "isssi", $new_user_id, $user_type, $gender, $birth_year, $old_user_id);
  $did_execute = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  // store result of query (i.e., whether it happened or not)
  $msg = "";
  if ($did_execute) {
    $msg = "Your update to rider ". $new_user_id . " completed successfully.";
  } else {
    $msg = "There was an error with your query. Please try again.";
  }
  // redirect the message to the user facing result page
  $_SESSION["msg"] = $msg;
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
  exit;
}
// if old_user_id is not set, redirect to the main admin page so they can start another search
else {
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php");
  exit;
}
?>