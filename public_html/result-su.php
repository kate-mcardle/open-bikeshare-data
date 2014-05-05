<!--  displays results of super user query -->
<?php
$page_type = "superuser";
include("protected.php");
// if the user submitted a non-empty query, process it
if (isset($_GET["query"]) && $_GET["query"] != "") {
  include "../mysql_connect.php";
  // unpack the query
  $query = $_GET["query"];
  // process query
  $stmt = mysqli_prepare($con, $query);
  // execute query and store result
  $did_execute = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  // store the result of the query
  if ($did_execute) {
    $msg = "Your query completed successfully.";
  } else {
    $msg = "There was an error with your query. Please try again.";
  }
}
// if user did not submit a query, redirect them to the admin-su page
else {
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin-su.php");
  exit;
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
    <title>OpenBikeshareData: Superuser Query Result</title>
  </head>
  <body>
    <!-- display the results of the query to the user -->
    <?php include("superuser_header.php"); ?>
    <div class="container">
      <br><br><br>
      <h3><?= $msg ?></h3>
      <p>Your query was:</p>
      <p><?= $query ?></p>
      <!-- link to do another query -->
      <a href="/~group2_trips/admin-su.php"><button type="button" class="btn btn-primary">Submit another query</button></a>
    </div>
  </body>
</html>