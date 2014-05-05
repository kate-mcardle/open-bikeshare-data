<?php
  function refValues($arr){
      if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
      {
          $refs = array();
          foreach($arr as $key => $value)
              $refs[$key] = &$arr[$key];
          return $refs;
      }
      return $arr;
  }

	include "../mysql_connect.php";
	$table = $_GET["table"];
	$trip_feature = $_GET["trip_feature"];
	$popularity = $_GET["popularity"];
	$group_timestamps = $_GET["group_timestamps"];
	$n_results = $_GET["n_results"];

	// check to make sure the parameters are as expected
	$valid_tables = array("riders", "stations", "trips");
	$valid_timestamps_groups = array("hour","minute");
	$valid_trip_features = array("start_time","stop_time","from_station_id","to_station_id","duration");
	$valid_popularity = array("asc","desc");
  if ((!in_array($table, $valid_tables)) || (!in_array($group_timestamps, $valid_timestamps_groups)) || (!in_array($trip_feature, $valid_trip_features)) || (!in_array($popularity, $valid_popularity))) {
    # don't let query execute
    header('Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php');
    exit;
  }

  // only on trips table, only on start or end time
  $query = "SELECT " . $group_timestamps . "(trips." . $trip_feature . ") , COUNT(*) as number_of_trips ";
  $query .= "FROM trips ";
  // if filters require joining:
  if (isset($_GET['from_capacity']) && strlen($_GET['from_capacity']) > 0) {
    $query .= ", stations AS from_stations ";
  }
  if (isset($_GET['to_capacity']) && strlen($_GET['to_capacity']) > 0) {
    $query .= ", stations AS to_stations ";
  }
  // if user checks rider type, add in rider table to be joined
  if ((isset($_GET['rider_type']) && count($_GET['rider_type']) == 1) || (isset($_GET['gender']) && count($_GET['gender']) == 1) || (isset($_GET['birth_year']) && count($_GET['birth_year']) == 1)) {
    $query .= ", riders ";
  }
  $param_type = "";
  $params = array();

  $connector = "WHERE";

  // FILTERS:
  if (isset($_GET['months']) && count($_GET['months']) > 0) {
    $months = $_GET['months'];
    $params = array_merge($params, $months);
    $param_type .= str_repeat("i", count($months));
    $qs = str_repeat("?, ", count($months)-1) . "?";
    $query .= $connector . " MONTH(trips.start_time) IN (" . $qs . ") ";
    $connector = "AND";
  }

  if (isset($_GET['days']) && count($_GET['days']) > 0) {
    $days = $_GET['days'];
    $params = array_merge($params, $days);
    $param_type .= str_repeat("i", count($days));
    $qs = str_repeat("?, ", count($days)-1) . "?";
    $query .= $connector . " WEEKDAY(trips.start_time) IN (" . $qs . ") ";
    $connector = "AND";
  }

  if (isset($_GET['time_window']) && strlen($_GET['time_window']) > 0) {
    $window_array = explode(',', $_GET['time_window']);
    $params = array_merge($params, $window_array);
    $param_type .= "ii";
    $query .= $connector . " HOUR(trips.start_time) >= ? AND HOUR(trips.start_time) <= ? ";
    $connector = "AND";
  }

  if (isset($_GET['duration']) && strlen($_GET['duration']) > 0) {
    $duration_array = explode(',', $_GET['duration']);
    $params = array_merge($params, $duration_array);
    $param_type .= "ii";
    $query .= $connector . " (TIMESTAMPDIFF(MINUTE, trips.start_time, trips.stop_time)) >= ? AND (TIMESTAMPDIFF(MINUTE, trips.start_time, trips.stop_time)) <= ? ";
    $connector = "AND";
  }

  if (isset($_GET['from_station_filter'])) {
    $from_station_id = $_GET['from_station_filter'];
    array_push($params, $from_station_id);
    $param_type .= "i";
    $query .= $connector . " trips.from_station_id = ? ";
    $connector = "AND";
  }
  if (isset($_GET['to_station_filter'])) {
    $to_station_id = $_GET['to_station_filter'];
    array_push($params, $to_station_id);
    $param_type .= "i";
    $query .= $connector . " trips.to_station_id = ? ";
    $connector = "AND";
  }
  // if the start station capacity has been specified, add it to the query
  if (isset($_GET['from_capacity']) && strlen($_GET['from_capacity']) > 0) {
    // join the trips table with the from_stations table, on from_station_id
    $query .= $connector . " trips.from_station_id = from_stations.id ";
    $connector = "AND";
    $capacity_array = explode(',', $_GET['from_capacity']);
    $params = array_merge($params, $capacity_array);
    $param_type .= "ii";
    // filter results by from_station_capacity
    $query .= $connector . " from_stations.bike_capacity >= ? AND from_stations.bike_capacity <= ? ";
    
  }
  // if the end station capacity has been specified, add it to the query
  if (isset($_GET['to_capacity']) && strlen($_GET['to_capacity']) > 0) {
    // join the trips table with the from_stations table, on from_station_id
    $query .= $connector . " trips.to_station_id = to_stations.id ";
    $connector = "AND";
    $capacity_array = explode(',', $_GET['to_capacity']);
    $params = array_merge($params, $capacity_array);
    $param_type .= "ii";
    // filter results by from_station_capacity
    $query .= $connector . " to_stations.bike_capacity >= ? AND to_stations.bike_capacity <= ? ";
    $connector = "AND";
  }
  $riders_joined = False;
  // if one rider type has been specified, add the specification to the query
  if (isset($_GET['rider_type']) && count($_GET['rider_type']) == 1) {
    // join the trips table with the from_stations table, on from_station_id
    if ($riders_joined == False) {
      $query .= $connector . " trips.rider_id = riders.id ";
      $connector = "AND";
      $riders_joined = True;
    }
    $rider_type = $_GET['rider_type'][0];
    array_push($params, $rider_type);
    $param_type .= "s";
    // filter results by from_station_capacity
    $query .= $connector . " riders.rider_type = ? ";
    $connector = "AND";

  }
  // if gender has been specified, add the gender to the query
  if (isset($_GET['gender']) && count($_GET['gender']) == 1) {
    // join the trips table with the from_stations table, on from_station_id
    if ($riders_joined == False) {
      $query .= $connector . " trips.rider_id = riders.id ";
      $connector = "AND";
      $riders_joined = True;
    }
    $gender = $_GET['gender'][0];
    array_push($params, $gender);
    $param_type .= "s";
    // filter results by from_station_capacity
    $query .= $connector . " riders.gender = ? ";
    $connector = "AND";
  }
  // birth year
  if (isset($_GET['birth_year']) && count($_GET['birth_year']) == 1) {
    // join the trips table with the from_stations table, on from_station_id
    if ($riders_joined == False) {
      $query .= $connector . " trips.rider_id = riders.id ";
      $connector = "AND";
      $riders_joined = True;
    }
    $birth_year_array = explode(',', $_GET['birth_year']);
    $params = array_merge($params, $birth_year_array);
    $param_type .= "ss";
    // filter results by from_station_capacity
    $query .= $connector . " riders.birth_year >= ? and riders.birth_year <= ? ";
    $connector = "AND";
  }

	$query .= "GROUP BY " . $group_timestamps . "(" . $table . "." . $trip_feature .") ";
	$query .= "ORDER BY number_of_trips ";
	$query .= $popularity . " ";
	$query .= "LIMIT ?";
  $param_type .= "i";
  echo $query;
  array_push($params, $n_results);
  echo "<br>" ;
  print_r($params);
  print_r($param_type);
  $stmt = mysqli_prepare($con, $query);
  call_user_func_array('mysqli_stmt_bind_param', array_merge (array($stmt, $param_type), refValues($params)));
  // mysqli_stmt_bind_param($stmt, "iiii", $months[0], $months[1], $months[2], $n_results);

  // mysqli_stmt_bind_param($stmt, "i", $n_results);

  mysqli_stmt_execute($stmt);

  mysqli_stmt_bind_result($stmt, $timestamp, $number_of_trips);
?>
<html>
	<body>
	<h4>Results</h4>
  The most popular start time(s) for trips:<br>
  <table>
  <tr><th><?= ucfirst($group_timestamps) ?> </th><th>Number of trips</th></tr>
<?php
  $no_results = True;
  while (mysqli_stmt_fetch($stmt)){
    $no_results = False;
  	if ($timestamp == 0){
  		$formatted_time = "12 a.m.";
  	} elseif ($timestamp < 12){
  		$formatted_time  = $timestamp . " a.m.";
  	} elseif ($timestamp == 12){
  		$formatted_time = "12 p.m.";
  	} else {
  		$timestamp = $timestamp - 12;
  		$formatted_time  = $timestamp . " p.m.";
  	}
  	echo "<tr><td> " . $formatted_time . "</td>";
  	echo "<td> " . $number_of_trips . "</td></tr>";
  }
  if ($no_results){
     echo "<tr><td> No results </td></tr>"; 
  }
?>
  </table>
  </body>
</html>