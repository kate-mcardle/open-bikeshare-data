<!-- This file is the main file for this website. It is the only page a non-admin user will visit.
			It contains the search form for the user to query the database, and it has an include directive to the results file (perform_search.php).
			The main search form gets displayed in stages, because the second question the user will be asked depends on his answer to the first
			question, and so on. Javascript/jquery functions are used to implement this dynamic functionality.
			The second search form is to add filters to the user's main search. All options are displayed at once, but the form is disabled
			until the user has performed the main search.
			Because the results are displayed on this page, we use a significant amount of php to prepopulate the search forms with the search the user
			has just done.
-->
<?php
include "../mysql_connect.php";

# From the database, get an array of valid stations
$query = 'SELECT stations.id, stations.name FROM stations ORDER BY stations.name ASC';
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $station_id, $station_name);
$stations = array();
while (mysqli_stmt_fetch($stmt)) {
	$stations[$station_id] = $station_name;
}

# From the database, get maximum bike capacity for any station
$query = 'SELECT MAX(stations.bike_capacity) FROM stations';
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $capacity);
while (mysqli_stmt_fetch($stmt)) {
	$max_capacity = $capacity;
}
# From the database, get minimum and maximum birth year of any rider
$query = 'SELECT MIN(riders.birth_year), MAX(riders.birth_year) FROM riders WHERE riders.birth_year != "NA"';
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $first_birth_year, $last_birth_year);
while (mysqli_stmt_fetch($stmt)) {
	$min_birth_year = $first_birth_year + 0;
	$max_birth_year = $last_birth_year + 0;
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
    <style>  
    	#time_window {
    		width: 200px;
  		}
  		#duration {
    		width: 200px;
  		}
  		 #n_results {
    		width: 200px;
  		}
  		#from_capacity {
    		width: 200px;
  		}
  		#to_capacity {
    		width: 200px;
  		}
  		#birth_year {
    		width: 200px;
  		}
  	</style>
		<title>OpenBikeshareData</title>
	</head>
	<body>
	<div class="container">
		<?php
		// if this page is being viewed after a user has submitted a query, use these javascript functions (end of file) to display the search that was submitted
		if (isset($_GET["submitted"])) {
			?>
			<script>
			$(document).ready(function() { 
				display_table_features(true);
				display_popularity(true);
				display_groups(true);
				display_n_results(true); 
			}); 
			</script>
			<?php
		}
		?>
		<h1>OpenBikeshareData: Search for trends in Chicago bikeshare usage</h1>
		<!-- Headers for each of the three sections of the page -->
		<div class="row">
			<div class="col-md-4"><h3>Trend Search&nbsp;&nbsp;&nbsp;&nbsp;<?php
					// only display the "clear form" button if the form is currently filled out
					if (isset($_GET["submitted"])) {
						?>
						<button type="button" onclick="clear_form()" class="btn btn-warning">Clear Form</button><br>
						<?php
					}
					?></h3></div>
			<div class="col-md-5"><h3>Add Filters&nbsp;&nbsp;&nbsp;&nbsp;<?php
				// only display the "clear filters" button if the trends form is currently filled out
					if (isset($_GET["submitted"])) {
						?>
						<button type="button" onclick="clear_filters()" class="btn btn-warning">Clear Filters</button><br>
						<?php
					}
					?></h3></div>
			<div class="col-md-3"><h3>Results</h3></div>
		</div>
		<div class="row">
			<!-- Form to search for trends -->
			<div id="trends" class="col-md-4">
				<form class="form form-validate" role="form" action="">
					<div class="form-group">
					<label for="table" class="control-label">What do you want to search?</label><br>
						<!-- Use javascript (end of file) to pull up the next part of the form, once the table has been selected -->
				    <select required class ="form-control" name="table" id="table" onchange="display_table_features(false)">
				    	<!-- Use php to choose which value appears as selected on the form, depending on if/what the user has selected: -->
						    	<!-- If the user hasn't made a selection yet, have a blank row display -->
				      <option <?php if (isset($_GET['table']) == False) { ?>selected<?php } ?> disabled hidden value=''></option>
						      <!-- Otherwise, the user has already submitted a form and we want to display the row he selected -->
				      <option <?php if (isset($_GET['table']) && ($_GET['table'] == "trips")) { ?>selected<?php } ?> value="trips">Trips</option>
			        <option <?php if (isset($_GET['table']) && ($_GET['table'] == "riders")) { ?>selected<?php } ?> value="riders">Riders</option>
			        <option <?php if (isset($_GET['table']) && ($_GET['table'] == "stations")) { ?>selected<?php } ?> value="stations">Stations</option>
			      </select><br>
			    </div>
			    <!-- Placeholder divs for the parts of the form that will be added by javascript (end of file) -->
		      <div class="form-group" id="table_features"></div>
		      <div class="form-group" id="pop"></div>
		      <div class="form-group" id="groups"></div>
		      <div class="form-group" id="results"></div>
		      <div class="form-group" id="submit"></div>
		    </form>
	     </div>
	     <!-- Form to add filters to search -->
	     <div id="filters" class="col-md-5">
	     	<form class="form form-validate" role="form" action="">
				<?php
					// Disable the entire form if no search has been performed yet
					if (!isset($_GET["submitted"])) {
						?>	     		
		     		<fieldset disabled>
	     			<?php
	     		}
	     		?>
	     		<!-- The filters section is quite long, so default each sub-section as collapsed (source: Bootstrap)-->
	     		<div class="panel-group" id="accordion">
	     			<!-- Filter by time -->
	     			<div class="panel panel-default">
	     				<div class="panel-heading">
					     	<h4 class="panel-title">
					     		<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
					     			By Time ...
					     		</a>
					     	</h4>
					    </div>
					    <!-- Once a search has been performed, display the filter options using "in" option in the class -->
					    <div id="collapseOne" class="panel-collapse collapse <?php if (isset($_GET["submitted"])) { ?>in <?php } ?>">
					    	<div class="panel-body">
						     	<div class="form-group">
						     		<!-- Restrict search to trips that started in the selected months -->
							     	<label><b>Months:</b></label><br>
							     	<label class="checkbox-inline"><input type="checkbox" name="months[]" value="01" <?php if (isset($_GET['months']) && in_array("01", $_GET['months'])) { ?>checked <?php } ?>>1</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="02" <?php if (isset($_GET['months']) && in_array("02", $_GET['months'])) { ?>checked <?php } ?>>2</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="03" <?php if (isset($_GET['months']) && in_array("03", $_GET['months'])) { ?>checked <?php } ?>>3</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="04" <?php if (isset($_GET['months']) && in_array("04", $_GET['months'])) { ?>checked <?php } ?>>4</input></label>
							     	<label class="checkbox-inline"><input type="checkbox" name="months[]" value="05" <?php if (isset($_GET['months']) && in_array("05", $_GET['months'])) { ?>checked <?php } ?>>5</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="06" <?php if (isset($_GET['months']) && in_array("06", $_GET['months'])) { ?>checked <?php } ?>>6</input></label><br>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="07" <?php if (isset($_GET['months']) && in_array("07", $_GET['months'])) { ?>checked <?php } ?>>7</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="08" <?php if (isset($_GET['months']) && in_array("08", $_GET['months'])) { ?>checked <?php } ?>>8</input></label>
							     	<label class="checkbox-inline"><input type="checkbox" name="months[]" value="09" <?php if (isset($_GET['months']) && in_array("09", $_GET['months'])) { ?>checked <?php } ?>>9</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="10" <?php if (isset($_GET['months']) && in_array("10", $_GET['months'])) { ?>checked <?php } ?>>10</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="11" <?php if (isset($_GET['months']) && in_array("11", $_GET['months'])) { ?>checked <?php } ?>>11</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="months[]" value="12" <?php if (isset($_GET['months']) && in_array("12", $_GET['months'])) { ?>checked <?php } ?>>12</input></label>
									</div>
									<div class="form-group">
										<!-- Restrict search to trips that started in the selected days of week -->
							     	<label><b>Days:</b></label><br>
							     	<label class="checkbox-inline"><input type="checkbox" name="days[]" value="0" <?php if (isset($_GET['days']) && in_array("0", $_GET['days'])) { ?>checked <?php } ?>>M</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="days[]" value="1" <?php if (isset($_GET['days']) && in_array("1", $_GET['days'])) { ?>checked <?php } ?>>T</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="days[]" value="2" <?php if (isset($_GET['days']) && in_array("2", $_GET['days'])) { ?>checked <?php } ?>>W</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="days[]" value="3" <?php if (isset($_GET['days']) && in_array("3", $_GET['days'])) { ?>checked <?php } ?>>T</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="days[]" value="4" <?php if (isset($_GET['days']) && in_array("4", $_GET['days'])) { ?>checked <?php } ?>>F</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="days[]" value="5" <?php if (isset($_GET['days']) && in_array("5", $_GET['days'])) { ?>checked <?php } ?>>S</input></label>
										<label class="checkbox-inline"><input type="checkbox" name="days[]" value="6" <?php if (isset($_GET['days']) && in_array("6", $_GET['days'])) { ?>checked <?php } ?>>S</input></label>
									</div>
						     	<div class="form-group">
						     		<!-- Restrict search to trips that started and ended in the selected hours -->
							     	<label for="time_window" class="control-label"><b>Time Window:</b></label><br>
							      Start hour: <b>0</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span2" name="time_window" value="<?php if (isset($_GET["time_window"]) && strlen($_GET['time_window']) > 0) { echo $_GET["time_window"]; } ?>" data-slider-min="0" data-slider-max="24" data-slider-step="1" data-slider-value="<?php if (isset($_GET["time_window"]) && strlen($_GET['time_window']) > 0) { echo "[" . $_GET["time_window"] . "]"; } else { echo "[0,24]"; }?>" data-slider-tooltip="show" id="time_window" >&nbsp;&nbsp;&nbsp;&nbsp;<b>24</b> End hour<br>
						   	 	</div>
						   	 	<div class="form-group">
						   	 		<!-- Restrict search to trips with duration in the given range -->
							     	<label for="duration" class="control-label"><b>Duration:</b></label><br>
							      Minutes: <b>0</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span2" name="duration" value="<?php if (isset($_GET["duration"]) && strlen($_GET['duration']) > 0) { echo $_GET["duration"]; } ?>" data-slider-min="0" data-slider-max="1440" data-slider-step="5" data-slider-value="<?php if (isset($_GET["duration"]) && strlen($_GET['duration']) > 0) { echo "[" . $_GET["duration"] . "]"; } else { echo "[0,1440]"; } ?>" data-slider-tooltip="show" id="duration" >&nbsp;&nbsp;&nbsp;&nbsp;<b>1440</b><br>
						   	 	</div>
						   	</div>
						  </div>
						</div>
						<!-- Filter by stations -->
	     			<div class="panel panel-default">
	     				<div class="panel-heading">
					     	<h4 class="panel-title">
					     		<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
					     			By Station ...
					     		</a>
					     	</h4>
					    </div>
					    <div id="collapseTwo" class="panel-collapse collapse <?php if (isset($_GET["submitted"])) { ?>in <?php } ?>">
					    	<div class="panel-body">
						     	<div class="form-group">
						     		<!-- Restrict search to trips that started from the selected station -->
							   	 <label for="from_station_filter" class="control-label"><b>Start station:</b></label><br>
							   	 <select class="form-control" name="from_station_filter" id="from_station_filter">
									 	<option <?php 
								      	if (isset($_GET['from_station_filter']) == False) {
								      ?>selected<?php } ?> disabled hidden value=''></option>
							   	 <?php 
							   	 foreach ($stations as $station_id => $station_name) {
							   	 	?>
							   	 	<option <?php 
								      	if (isset($_GET['from_station_filter']) && $_GET['from_station_filter'] == $station_id) {
								      ?>selected<?php } ?> value="<?= $station_id ?>"><?= $station_name ?></option>
							   	 	<?php }
							   	 ?>
							   	 </select><br>
						   		</div>
						   		<div class="form-group">
						   		 <!-- Restrict search to trips that ended at the selected station -->
							   	 <label for="to_station_filter" class="control-label"><b>End station:</b></label><br>
							   	 <select class="form-control" name="to_station_filter" id="to_station_filter">
									 	<option <?php 
								      	if (isset($_GET['to_station_filter']) == False) {
								      ?>selected<?php } ?> disabled hidden value=''></option>
							   	 <?php 
							   	 foreach ($stations as $station_id => $station_name) {
							   	 	?>
							   	 	<option <?php 
								      	if (isset($_GET['to_station_filter']) && $_GET['to_station_filter'] == $station_id) {
								      ?>selected<?php } ?> value="<?= $station_id ?>"><?= $station_name ?></option>
							   	 	<?php }
							   	 ?>
							   	 </select><br>
							   	</div>
						   	 <!-- Capacity -->
						   	 <div class="form-group">
						   	 	<!-- Restrict search to trips that started at a station with bike capacity in the given range -->
							   	 <label for="from_capacity" class="control-label"><b>Start Station Capacity:</b></label><br>
							     Bikes: <b>0</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span2" name="from_capacity" value="<?php if (isset($_GET["from_capacity"]) && strlen($_GET['from_capacity']) > 0) { echo $_GET["from_capacity"]; } ?>" data-slider-min="0" data-slider-max="<?= $max_capacity ?>" data-slider-step="1" data-slider-value="<?php if (isset($_GET["from_capacity"]) && strlen($_GET['from_capacity']) > 0) { echo "[" . $_GET["from_capacity"] . "]"; } else { echo "[0," . $max_capacity . "]"; }?>" data-slider-tooltip="show" id="from_capacity" >&nbsp;&nbsp;&nbsp;&nbsp;<b><?= $max_capacity ?></b><br>
								 </div>
								 <!-- Capacity -->
								 <div class="form-group">
								 	<!-- Restrict search to trips that ended at a station with bike capacity in the given range -->
									 <label for="to_capacity" class="control-label"><b>End Station Capacity:</b></label><br>
									 Bikes: <b>0</b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span2" name="to_capacity" value="<?php if (isset($_GET["to_capacity"]) && strlen($_GET['to_capacity']) > 0) { echo $_GET["to_capacity"]; } ?>" data-slider-min="0" data-slider-max="<?= $max_capacity ?>" data-slider-step="1" data-slider-value="<?php if (isset($_GET["to_capacity"]) && strlen($_GET['to_capacity']) > 0) { echo "[" . $_GET["to_capacity"] . "]"; } else { echo "[0," . $max_capacity . "]"; }?>" data-slider-tooltip="show" id="to_capacity" >&nbsp;&nbsp;&nbsp;&nbsp;<b><?= $max_capacity ?></b><br>
						     </div>
						   	</div>
						  </div>
						</div>
						<!-- Filter by riders -->
	     			<div class="panel panel-default">
	     				<div class="panel-heading">
					     	<h4 class="panel-title">
					     		<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
					     			By Rider ...
					     		</a>
					     	</h4>
					    </div>
					    <div id="collapseThree" class="panel-collapse collapse <?php if (isset($_GET["submitted"])) { ?>in <?php } ?>">
					    	<div class="panel-body">
						     <div class="form-group">
						     	<!-- Restrict search to trips taken by subscribers or non-subscribers -->
							     <label><b>Type:</b></label><br>
							     <label class="checkbox-inline"><input type="checkbox" name="rider_type[]" value="Subscriber" <?php if (isset($_GET['rider_type']) && in_array("Subscriber", $_GET['rider_type'])) { ?>checked <?php } ?>>Subscriber</input></label>
									 <label class="checkbox-inline"><input type="checkbox" name="rider_type[]" value="Non-Subscriber" <?php if (isset($_GET['rider_type']) && in_array("Non-Subscriber", $_GET['rider_type'])) { ?>checked <?php } ?>>Non-Subscriber</input></label>
								 </div>
								 <!-- Gender -->
								 <div class="form-group">
								 	<!-- Restrict search to trips taken by women or men -->
							     <label><b>Gender (Subscribers Only):</b></label><br>
							     <label class="checkbox-inline"><input type="checkbox" name="gender[]" value="Female" <?php if (isset($_GET['gender']) && in_array("Female", $_GET['gender'])) { ?>checked <?php } ?>>Female</input></label>
									 <label class="checkbox-inline"><input type="checkbox" name="gender[]" value="Male" <?php if (isset($_GET['gender']) && in_array("Male", $_GET['gender'])) { ?>checked <?php } ?>>Male</input></label>
								 </div>
								 <!-- Age -->
								 <div class="form-group">
								 	<!-- Restrict search to trips taken by people born in the given year range -->
									 <label for="birth_year" class="control-label"><b>Birthyear (Subscribers Only):</b></label><br>
									 <b><?= $min_birth_year ?></b>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span2" name="birth_year" value="<?php if (isset($_GET["birth_year"]) && strlen($_GET['birth_year']) > 0) { echo $_GET["birth_year"]; } ?>" data-slider-min="<?= $min_birth_year ?>" data-slider-max="<?= $max_birth_year ?>" data-slider-step="1" data-slider-value="<?php if (isset($_GET["birth_year"]) && strlen($_GET['birth_year']) > 0) { echo "[" . $_GET["birth_year"] . "]"; } else { echo "[ " . $min_birth_year . "," . $max_birth_year . "]"; }?>" data-slider-tooltip="show" id="birth_year" >&nbsp;&nbsp;&nbsp;&nbsp;<b><?= $max_birth_year ?></b><br>
								 </div>
								</div>
							</div>
						</div>
					</div>
				<?php
					// If a search has previously been done, add the search parameters to this form
					if (isset($_GET["submitted"])) {
						?>
						<input type="hidden" value="true" name="submitted"></input>
						<input type="hidden" value="<?= $_GET['table'] ?>" name="table"></input>
						<input type="hidden" value="<?= $_GET['trip_feature'] ?>" name="trip_feature"></input>
						<input type="hidden" value="<?= $_GET['popularity'] ?>" name="popularity"></input>
						<input type="hidden" value="<?= $_GET['group_timestamps'] ?>" name="group_timestamps"></input>
						<input type="hidden" value="<?= $_GET['n_results'] ?>" name="n_results"></input>
						<?php
					}
				?>
				<button type="submit" class="btn btn-primary">Apply Filters</button> 
				<?php if (!isset($_GET["submitted"])) {
						?>	     		
		     		</fieldset>
	     			<?php
	     		}
	     		?>
	  		</form>
	  	</div>
	  	<div id="results" class="col-md-3">
	  		<!-- See perform_search.php for the code that is used here -->
			<?php 
			include "perform_search.php";
			?>
			</div>
		</div>
	<script>
	function display_table_features(was_submitted) {
		// this function is called when a user selects a table to do a search on, and it displays the next question to ask the user
		var table = document.getElementById("table").value;
		// make sure that these divs are emptied of any html content they previously had:
    $("#table_features").empty();
    $("#pop").empty();
    $("#groups").empty();
    $("#results").empty();
    $("#submit").empty();
    // start to build up the html that will go in the div table_features, which corresponds to the form that asks the user which attribute from
    // the table "trips" he is interested in querying on
    var features_html = '<label for="trip_feature" class="control-label">What about ' + table + ' are you interested in?</label><br>';
    var selected_feature = "";
    // switch on the table the user selected; for now, we are only handling the use case when the user selects "trips"
    switch(table) {
    case "trips":
    	// when the user selects a trip feature, use a javascript function (display_popularity) to add the next form question
       features_html += '<select required class="form-control" name="trip_feature" id="trip_feature" onchange="display_popularity(false)">';
       // the parameter was_submitted represents whether we are handling the case that the user has already submitted the search query
       // if the user hasn't submitted a query yet, then was_submitted is false, and we want any dropdown boxes to show a blank row
       if (was_submitted == false) {
         features_html += '<option selected disabled hidden value=""></option>';
       } else { 
       	// if the user has submitted a query, we store the value of the trip_feature in selected_feature
         features_html += '<option disabled hidden value=""></option>';
         selected_feature = "<?php if (isset($_GET['trip_feature'])) { echo $_GET['trip_feature']; } ?>";
       }
       // use the value of selected_feature to determine which row in the dropdown should be displayed
       if (selected_feature == "start_time") {
         features_html += '<option selected value="start_time">Start time</option>';
       } else {
         features_html += '<option value="start_time">Start time</option>';
       }

       if (selected_feature == "stop_time") {
         features_html += '<option selected value="stop_time">End time</option>';
       } else {
         features_html += '<option value="stop_time">End time</option>';
       }

        if (selected_feature == "duration") {
         features_html += '<option selected value="duration">Duration</option>';
        } else {
         features_html += '<option value="duration">Duration</option>';
        }
        
        if (selected_feature == "from_station_id") {
         features_html += '<option selected value="from_station_id">Start station</option>';
        } else {
         features_html += '<option value="from_station_id">Start station</option>';
        }
        
        if (selected_feature == "to_station_id") {
         features_html += '<option selected value="to_station_id">End station</option>';
        } else {
         features_html += '<option value="to_station_id">End station</option>';
        }
        
        features_html += '</select><br>';
        break;
       // TODO: add the cases for riders, stations
      default:
       features_html += "You are not allowed to search for that!";   
    }
    $('#table_features').append(features_html);
  }

  function get_feature_formatted(was_submitted) {
  	// this function determines which table attribute has been selected ("feature") and formats the string to be displayed nicely to the user
   var feature = "";
   if (was_submitted == false) {
     var table = document.getElementById("table").value;
     var table_feature = "";
     switch(table) {
       case "trips":
         table_feature = "trip_feature";
         break;
       case "riders":
         table_feature = "rider_feature";
         break;
       case "stations":
         table_feature = "station_feature";
         break;
       default:
         return NULL;
     }
     feature = document.getElementById(table_feature).value;     
   }
   else {
     feature = "<?php if (isset($_GET['trip_feature'])) { echo $_GET['trip_feature']; } ?>";
   }
   var feature_formatted = feature.replace("_id", "");
   feature_formatted = feature_formatted.replace("from_", "starting ");
   feature_formatted = feature_formatted.replace("to_", "ending ");
   feature_formatted = feature_formatted.replace("_", " ");
   return feature_formatted;
  }

  function display_popularity(was_submitted) {
  	// this function is called when a user selects which table attribute he is interested in querying, and it displays the next question to ask the user
   $("#pop").empty();
   $("#groups").empty();
   $("#results").empty();
   $("#submit").empty();

   feature_formatted = get_feature_formatted(was_submitted);

   var pop_html = '<label for="popularity" class="control-label">Are you interested in the most or least popular ' + feature_formatted + 's?</label><br>';
   var selected_pop = "";

   pop_html += '<select required class="form-control" name="popularity" id="popularity" onchange="display_groups(false)">';
   if (was_submitted == false) {
     pop_html += '<option selected disabled hidden value=""></option>';
   } else { 
     pop_html += '<option disabled hidden value=""></option>';
     selected_pop = "<?php if (isset($_GET['popularity'])) { echo $_GET['popularity']; } ?>";
   }

   if (selected_pop == "desc") {
     pop_html += '<option selected value="desc">Most popular</option>';
   } else {
     pop_html += '<option value="desc">Most popular</option>';
   }
    
   if (selected_pop == "asc") {
     pop_html += '<option selected value="asc">Least popular</option>';
   } else {
     pop_html += '<option value="asc">Least popular</option>';
   }   
    
    pop_html += '</select><br>';
    $('#pop').append(pop_html);
 }

	function display_groups(was_submitted) {
		// this function gets called when the user has selected most/least popular in the previous question, and it displays the next question to ask the user
   $("#groups").empty();
   $("#results").empty();
   $("#submit").empty();

   feature_formatted = get_feature_formatted(was_submitted);
   // if feature is not start or stop time, call display_n_results()
   if (feature_formatted.search("time") == -1) {
     display_n_results();
     return NULL;
   }

   // var feature_formatted = feature.replace("_", " ");
   var group_html = '<label for="group_timestamps" class="control-label">How would you like to group ' + feature_formatted + 's?</label><br>';
   var selected_group = "";

   group_html += '<select required class="form-control" name="group_timestamps" id="group_timestamps" onchange="display_n_results(false)">';
   if (was_submitted == false) {
     group_html += '<option selected disabled hidden value=""></option>';
   } else {
     group_html += '<option disabled hidden value=""></option>';
     selected_group = "<?php if (isset($_GET['group_timestamps'])) { echo $_GET['group_timestamps']; } ?>";
   }
    
    if (selected_group == "hour") {
     group_html += '<option selected value="hour">By hour</option>';
    } else {
     group_html += '<option value="hour">By hour</option>';
    }

    if (selected_group == "minute") {
     group_html += '<option selected value="minute">By minute</option>';
    } else {
     group_html += '<option value="minute">By minute</option>';
    }

    group_html += '</select><br>';
    $('#groups').append(group_html);

  }

  function display_n_results(was_submitted) {
  	// this function gets called when the user has selected how to group the timestamps in the previous question, and it displays the final question to ask the user and the submit button
   $("#results").empty();
   $("#submit").empty();

   var results_html = '<label for="n_results" class="control-label">How many results would you like?</label><br>1&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="span2" id="n_results" value="';
   if (was_submitted == false) {
     results_html += '1" name="n_results" data-slider-min="1" data-slider-max="100" data-slider-value="1';
   } else {
     results_html += '<?php if (isset($_GET["n_results"])) { echo $_GET["n_results"]; } ?>" name="n_results" data-slider-min="1" data-slider-max="100" data-slider-value="<?php if (isset($_GET["n_results"])) { echo $_GET["n_results"]; } ?>';
   }
   results_html += '">&nbsp;&nbsp;&nbsp;&nbsp;100<br>';
   $('#results').append(results_html);
   $('#n_results').slider();
   var submit_html = '<input type="hidden" value="true" name="submitted"></input><br><button type="submit" class="btn btn-primary">Search</button>';
   $('#submit').append(submit_html);      
  }

  function clear_form() {
  	window.location = "http://holden.ischool.utexas.edu/~group2_trips/home.php";
  }

  function clear_filters() {
  	window.location = "http://holden.ischool.utexas.edu/~group2_trips/home.php?table=<?php if (isset($_GET['table'])) { echo $_GET['table']; } ?>&trip_feature=<?php if (isset($_GET['trip_feature'])) { echo $_GET['trip_feature']; } ?>&popularity=<?php if (isset($_GET['popularity'])) { echo $_GET['popularity']; } ?>&group_timestamps=<?php if (isset($_GET['group_timestamps'])) { echo $_GET['group_timestamps']; } ?>&n_results=<?php if (isset($_GET['n_results'])) { echo $_GET['n_results']; } ?>&submitted=true";
  }

	// bootstrap-based sliders for UI
	$('#time_window').slider();
	$('#duration').slider();
	$('#from_capacity').slider();
	$('#to_capacity').slider();
	$('#birth_year').slider();
	</script>
	</div>
	</body>
</html>

