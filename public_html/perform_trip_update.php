<?php
// set access level
$page_type = "admin";
include("protected.php");
// if there is an old trip id, it means we need to update the trips table
if (isset($_GET["old_trip_id"])) {
  include "../mysql_connect.php";
  // extract the updated features from the $_GET array and feed them in as parameters to the query
  $old_trip_id = $_GET["old_trip_id"];
  $new_trip_id = $_GET["new_trip_id"];
  $start_time = $_GET["start_time"];
  $stop_time = $_GET["stop_time"];
  $bike_id = $_GET["bike_id"];
  $from_station_id = $_GET["from_station_id"];
  $to_station_id = $_GET["to_station_id"];
  $rider_id = $_GET["rider_id"];
  // query to update the trip features
  $query = "UPDATE trips ";
  $query .= "SET trips.id = ?, trips.start_time = ?, trips.stop_time = ?, trips.bike_id = ?, trips.from_station_id = ?, trips.to_station_id = ?, trips.rider_id = ? ";
  $query .= "WHERE trips.id = ?";
  // prepare and execute query
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "issiiiii", $new_trip_id, $start_time, $stop_time, $bike_id, $from_station_id, $to_station_id, $rider_id, $old_trip_id);
  $did_execute = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  // store result of query (i.e., whether it happened or not)
  $msg = "";
  if ($did_execute) {
    $msg = "Your update to trip ". $station_id . " completed successfully.";
  } else {
    $msg = "There was an error with your query. Please try again.";
  }
  // redirect the message to the user facing result page
  $_SESSION["msg"] = $msg;
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
  exit;
} // if old_trip_id is not set, redirect to the main admin page so they can start another search
else {
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php");
  exit;
}
?>