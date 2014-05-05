<!-- performs query to get existing data and presents form to update  data -->

<?php
// check user's credentials
$page_type = "admin";
include("protected.php");
// if rider_to_edit is not set then redirect to admin.php because user has not specified that they want to edit a rider
if (!isset($_SESSION["rider_to_edit"])) {
  header('Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php');
  exit;
}   
// otherwise extract the result of the query for a rider's information
else {
  // save the old rider id just in case the user changes the rider id
  $old_user_id = $_SESSION["rider_to_edit"][0];
  $user_type = $_SESSION["rider_to_edit"][1];
  $gender = $_SESSION["rider_to_edit"][2];
  $birth_year = $_SESSION["rider_to_edit"][3];
  unset($_SESSION["rider_to_edit"]);
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
    <title>OpenBikeShareData: Update a Rider</title>
  </head>
  <body>
    <?php include("admin_header.php"); ?>
    <div class="container">
      <br><br><br>
      <!-- form to update a rider's information, pre-populated with rider's current information (current id, gender, rider type, and birth year) -->
      <h3>Update the rider's information:</h3>
      <form class="form form-validate" role="form" action="/~group2_trips/perform_rider_update.php">
        <table class="table">
          <tr><th>Rider ID</th><th>Rider Type</th><th>Gender</th><th>Birth Year</th></tr>
          <tr>
            <td>
              <label for="new_user_id"></label>
              <input required type="number" name="new_user_id" value="<?= $old_user_id ?>"></input>
            </td>
            <td>
              <label for="user_type"></label>
              <select class="form-control" required name="user_type">
                <option value="Subscriber" 
                <?php if ($user_type == "Subscriber") {
                  ?> selected="selected"
                <?php } ?>
                >Subscriber</option>
                <option value="Non-Subscriber"
                <?php if ($user_type == "Non-Subscriber") {
                  ?> selected="selected"
                <?php } ?>
                >Non-Subscriber</option>
              </select>
            </td>
            <td>
              <label for="gender"></label>
              <select class="form-control" required name="gender">
                <option value="Female" 
                <?php if ($gender == "Female") {
                  ?> selected="selected"
                <?php } ?>
                >Female</option>
                <option value="Male"
                <?php if ($gender == "Male") {
                  ?> selected="selected"
                <?php } ?>
                >Male</option>
                <option value=""
                <?php if ($gender == "") {
                  ?> selected="selected"
                <?php } ?>
                >NA</option>
              </select>
            </td>          
            <td>
              <label for="birth_year"></label>
              <input required type="text" name="birth_year" value="<?= $birth_year ?>"></input>
            </td>
          </tr>
        </table>
        <!-- keep track of rider's old id so be used in the update query on the page -->
        <input type="hidden" name="old_user_id" value="<?= $old_user_id ?>"/>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </body>
  </html>
