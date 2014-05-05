<!-- redirects to different pages based on admin user input in admin.php -->
<?php
// check credentials of page user
$page_type = "admin";
include("protected.php");

if (isset($_GET["table"])) {
  include "../mysql_connect.php";
  // figure out if user is updating or uploading data
  $action = $_GET["upload_update"];
  // the table they are updating/uploading
  $table = $_GET["table"];
  // in order to protect against malicious users, make sure the $table variable is a valid table in the database
  $valid_tables = array("riders", "stations", "trips");
  if (!in_array($table, $valid_tables)) {
    // don't let query execute if the $table variable is not in the $valid_tables array
    header('Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php');
    exit;
  }
  // UPDATE DATA
  // Note: we are assuming if id is non-empty then the user has to be updating data
  if (isset($_GET["id"])) {
    $id = $_GET["id"];
    // query that extracts the record with the user-specified id
    $query = "SELECT * ";
    $query .= "FROM " . $table . " ";
    $query .= "WHERE " . $table . ".id = ?";
    // prepare and execute query
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    // to extract results need to do a switch based on which table is being updated:
    // (necessary because every table has a different number of parameters)
    switch ($table) {
    case "riders":
      mysqli_stmt_bind_result($stmt, $user_id, $user_type, $gender, $birth_year);
      while (mysqli_stmt_fetch($stmt)) {
        // redirect to page to update-rider:
        $_SESSION['rider_to_edit'] = array($user_id, $user_type, $gender, $birth_year);
        header('Location: http://holden.ischool.utexas.edu/~group2_trips/update-rider.php');
        exit;
      }
      // if query was unsuccessful, store the message that it failed and redirect result-admin
      $_SESSION["msg"] = "Sorry, there is no rider with ID " . $id . ". Please try again.";
      header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
      exit;
      break;
    case "stations":
      mysqli_stmt_bind_result($stmt, $station_id, $station_name, $lat, $lng, $bike_capacity);
      while (mysqli_stmt_fetch($stmt)) {
        // redirect to page to update-station:
        $_SESSION['station_to_edit'] = array($station_id, $station_name, $lat, $lng, $bike_capacity);
        header('Location: http://holden.ischool.utexas.edu/~group2_trips/update-station.php');
        exit;
      }
      // if query was unsuccessful, store the message that it failed and redirect result-admin
      $_SESSION["msg"] = "Sorry, there is no station with ID " . $id . ". Please try again.";
      header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
      exit;      
      break;
    case "trips":
      mysqli_stmt_bind_result($stmt, $trip_id, $start_time, $stop_time, $bike_id, $from_station_id, $to_station_id, $rider_id);
      while (mysqli_stmt_fetch($stmt)) {
        // redirect to page to  update-trip:
        $_SESSION['trip_to_edit'] = array($trip_id, $start_time, $stop_time, $bike_id, $from_station_id, $to_station_id, $rider_id);
        header('Location: http://holden.ischool.utexas.edu/~group2_trips/update-trip.php');
        exit;
      }
      // if query was unsuccessful, store the message that it failed and redirect result-admin
      $_SESSION["msg"] = "Sorry, there is no trip with ID " . $id . ". Please try again.";
      header("Location: http://holden.ischool.utexas.edu/~group2_trips/result-admin.php");
      exit;      
      break;
    default:
      // otherwise redirect to admin.php so they can start over again
      header('Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php');
      exit;
    }
    mysqli_stmt_close($stmt);
  }
  // UPLOAD DATA
  if ($action == "upload"){
    // redirect to add-<table>.php based on which table the user selected
    switch ($table){
    case "riders":
      header('Location: http://holden.ischool.utexas.edu/~group2_trips/add-rider.php');
      exit;
      break;
    case "stations":
      header('Location: http://holden.ischool.utexas.edu/~group2_trips/add-station.php');
      exit;
      break;
    case "trips":
      header('Location: http://holden.ischool.utexas.edu/~group2_trips/add-trip.php');
      exit;
      break;
    default:
      header('Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php');
      exit;
    }
  }
}
?>