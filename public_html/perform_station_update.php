<?php
// Set permission level
$page_type = "admin";
include("protected.php");
// if there is an old station id, it means we need to update the stations table
if (isset($_GET["old_station_id"])) {
  include "../mysql_connect.php";
  $old_station_id = $_GET["old_station_id"];
  $new_station_id = $_GET["new_station_id"];
  $station_name = $_GET["station_name"];
  $lat = $_GET["lat"];
  $lng = $_GET["lng"];
  $bike_capacity = $_GET["bike_capacity"];
  // query to update the station features
  $query = "UPDATE stations ";
  $query .= "SET stations.id = ?, stations.name = ?, stations.lat = ?, stations.lng = ?, stations.bike_capacity = ? ";
  $query .= "WHERE stations.id = ?";
  // prepare and execute query
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "isddii", $new_station_id, $station_name, $lat, $lng, $bike_capacity, $old_station_id);
  $did_execute = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  // store result of query (i.e., whether it happened or not)
  $msg = "";
  if ($did_execute) {
    $msg = "Your update to station ". $station_name . " (station id " . $new_station_id . ") completed successfully.";
  } else {
    $msg = "There was an error with your query. Please try again.";
  }
  $_SESSION["msg"] = $msg;
  // redirect the message to the user facing result page
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
  exit;
}
// if old_user_id is not set, redirect to the main admin page so they can start another search
else {
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php");
  exit;
}
?>