<!-- form allows admin users to update or upload data -->
<?php
// check user's credentials
$page_type = "admin";

include("protected.php");
?>
  <html>
    <head>
      <!-- jQuery libraries -->
      <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="slider/css/slider.css" />
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
      <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
      <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
      <script type="text/javascript" src="slider/js/bootstrap-slider.js"></script>
      <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
      <title>OpenBikeshareData: Admin Data Management</title>
    </head>
    <body>
      <!-- header -->
      <?php include("admin_header.php"); ?>
      <div class="container">
        <br><br><br>
        <h3>Make updates to your company's bikeshare data:</h3>
        <!-- form where admin says what he wants to do with the data (i.e., update or upload data) -->
        <form class="form form-validate" role="form" action="/~group2_trips/admin_query.php">
          <!-- user slects if they want to upload or update data -->
          <div class="row">
          <div class="form-group col-sm-4">
            <label for="upload_update" class="control-label">What would you like to do?</label><br>
            <select required class="form-control" name="upload_update" id="upload_update" onchange="display_data_choice()">
              <option selected disabled hidden value=''></option>
              <option value="update">Update Data</option>
              <option value="upload">Upload Data</option>
            </select>
          </div></div>
          <!-- this div is updated in the javascript below and has to do with identifying if user wants to upload or update data-->
          <div class="row">
          <div class="form-group col-sm-4" id="what_data"></div></div>
          <!-- this div is updated in the javascript below and is where user enters ids of things they want to update --> 
          <div class="row">
          <div class="form-group col-sm-4" id="enter_id"></div></div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Go</button>
          </div>
        </form>
        <script>
        // javascript will dynamically show the user different choices based on what type of action they want to take
        function display_data_choice() {
          var upload_update = document.getElementById("upload_update").value;
          $("#what_data").empty();
          $("#enter_id").empty();
          // user selects which table they want to update or add data to
          var what_data = '<label for="table" class="control-label">What data do you want to ';
          what_data += upload_update;
          what_data += '?</label><br><select required class="form-control" name="table" id="table" onchange="display_id_enter()"><option selected disabled hidden value=""></option><option value="riders">Riders</option><option value="stations">Stations</option><option value="trips">Trips</option></select>';
          $('#what_data').append(what_data);
        }

        function display_id_enter() {
          var upload_update = document.getElementById("upload_update").value;
          var table = document.getElementById("table").value;
          var n = table.length;
          table = table.substring(0,n-1);
          // user enters the id of the trip, rider, or station they want to update
          $('#enter_id').empty();
          if (upload_update == "update") {
            var enter_id = '<label for="id" class="control-label">Enter the id of the ' + table + ' you want to update:</label><br><input required type="number" name="id"></input>';
            $('#enter_id').append(enter_id);
          }
        }
        </script>
      </div>
    </body>
  </html>
