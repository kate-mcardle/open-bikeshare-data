<!-- executes query to add new trip to trips table -->
<?php
// set permission level
$page_type = "admin";
include("protected.php");
// check to see if trip level variables are set
if (isset($_GET["trip_id"]) && isset($_GET["start_time"]) && isset($_GET["stop_time"]) && isset($_GET["bike_id"]) && isset($_GET["from_station_id"]) && isset($_GET["to_station_id"]) && isset($_GET["rider_id"])) {
  include "../mysql_connect.php";
  // store field values
  $trip_id = $_GET["trip_id"];
  $start_time = $_GET["start_time"];
  $stop_time = $_GET["stop_time"];
  $bike_id = $_GET["bike_id"];
  $from_station_id = $_GET["from_station_id"];
  $to_station_id = $_GET["to_station_id"];
  $rider_id = $_GET["rider_id"];
  // prep query to add a trip to the trips table
  $query = "INSERT into trips(id,start_time,stop_time,bike_id,from_station_id,to_station_id,rider_id) ";
  $query .= "VALUES (?,?,?,?,?,?,?)";
// execute insert
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "issiiii", $trip_id, $start_time, $stop_time, $bike_id, $from_station_id, $to_station_id ,$rider_id);
  $did_execute = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  // assess the results of the insert
  $msg = "";
  if ($did_execute) {
    $msg = "You successfully added trip ". $trip_id . " !";
  } else {
    $msg = "There was an error adding that trip. Please try again.";
  }
  // redirect the message ot the admin facing result page
  $_SESSION["msg"] = $msg;
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
  exit;
} // if the variables weren't set, redirect to the admin home page so the admin can start another session
else {
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php");
  exit;
}
?>