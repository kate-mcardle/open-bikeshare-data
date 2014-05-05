<!-- executes query to add new station to stations table -->
<?php
$page_type = "admin";
include("protected.php");
// only execute if all fields are filled out
if (isset($_GET["station_id"]) && isset($_GET["station_name"]) && isset($_GET["lat"]) && isset($_GET["lng"]) && isset($_GET["bike_capacity"]) ) {
  include "../mysql_connect.php";
  // store field values
  $station_id = $_GET["station_id"];
  $station_name = $_GET["station_name"];
  $lat = $_GET["lat"];
  $lng = $_GET["lng"];
  $bike_capacity = $_GET["bike_capacity"];
  // prepare query
  $query = "INSERT into stations(id,name,lat,lng,bike_capacity) ";
  $query .= "VALUES (?,?,?,?,?)";
  // execute query
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, "isddi", $station_id, $station_name, $lat, $lng, $bike_capacity);
  $did_execute = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  // store result of query
  $msg = "";
  if ($did_execute) {
    $msg = "You successfully added station ". $station_name . " (station id " . $station_id . ")!";
  } else {
    $msg = "There was an error adding that station. Please try again.";
  }
  // send result of query to result-admin
  $_SESSION["msg"] = $msg;
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
  exit;
} // if all fields are not filled out redirect to the admin page so they can start a new search
else {
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php");
  exit;
}
?>