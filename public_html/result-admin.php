<!-- displays result of upload/update data action to the user -->
<?php
// set permission level
$page_type = "admin";
include("protected.php");
// if $msg is set, extract $msg, which was the result of whatever query was performed
if (isset($_SESSION["msg"]) && $_SESSION["msg"]!=''){
  $msg = $_SESSION["msg"];
  unset($_SESSION["msg"]);
} else {
  // otherwise, a query was not performed so redirect to the admin page
  header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php");
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
    <title>OpenBikeshareData: Update Confirmation</title>
  </head>
  <body>
    <?php include("admin_header.php"); ?>
    <div class="container">
      <br><br><br>
      <!-- display query result -->
      <h3><?= $msg ?></h3>
      <!-- link to start a new query -->
      <a href="/~group2_trips/admin.php"><button type="button" class="btn btn-primary">Make another change</button></a>
  </div>
  </body>
</html>