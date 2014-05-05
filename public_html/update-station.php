<!-- performs query to get existing data and presents form to update  data -->
<?php
// set access level
$page_type = "admin";
include("protected.php");
// if station_to_edit is not set then redirect to admin.php because user has not specified that they want to edit a station
if (!isset($_SESSION["station_to_edit"])) {
  header('Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php');
  exit;
} // otherwise extract the result of the query for a station's information
else {
  // save the old station id just in case the user changes the station id
  $old_station_id = $_SESSION["station_to_edit"][0];
  $station_name = $_SESSION["station_to_edit"][1];
  $lat = $_SESSION["station_to_edit"][2];
  $lng = $_SESSION["station_to_edit"][3];
  $bike_capacity = $_SESSION["station_to_edit"][4];
  unset($_SESSION["station_to_edit"]);
}
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="slider/css/slider.css" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="slider/js/bootstrap-slider.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <title>OpenBikeShareData: Update a Station</title>
  </head>
  <body>
    <?php include("admin_header.php"); ?>
    <div class="container">
      <br><br><br>
      <h3>Update the station's information:</h3>
      <!-- form to update a station's information, pre-populated with station's current information (current id, lat, lng, name, bike capacity) -->
      <form class="form form-validate" role="form" action="perform_station_update.php">
        <table class="table">
          <tr><th>Station ID</th><th>Station Name</th><th>Latitude</th><th>Longitude</th><th>Bike Capacity</th></tr>
          <tr>
            <td>
              <label for="new_station_id"></label>
              <input required type="number" name="new_station_id" value="<?= $old_station_id ?>"></input>
            </td>       
            <td>
              <label for="station_name"></label>
              <input required type="text" name="station_name" value="<?= $station_name ?>"></input>
            </td>
            <td>
              <label for="lat"></label>
              <input required type="number" step="any" name="lat" value="<?= $lat ?>"></input>
            </td>
            <td>
              <label for="lng"></label>
              <input required type="number" step="any" name="lng" value="<?= $lng ?>"></input>
            </td>
            <td>
              <label for="bike_capacity"></label>
              <input required type="number" name="bike_capacity" value="<?= $bike_capacity ?>"></input>
            </td>
          </tr>
        </table>
        <!-- keep track of station's old id so be used in the update query on the page -->
        <input type="hidden" name="old_station_id" value="<?= $old_station_id ?>"/>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </body>
  </html>
