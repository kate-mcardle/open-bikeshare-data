<!-- form for users to enter username and password -->
<?php
session_start();
// if user is already logged in and redirect them to the appropriate page based on their access level
if(isset($_SESSION["access_level"])) {
  if ($_SESSION["access_level"] == "superuser") {
    header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin-su.php");
    exit;
  }
  if ($_SESSION["access_level"] == "admin") {
    header("Location: http://holden.ischool.utexas.edu/~group2_trips/admin.php");
    exit;
  }
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
    <title>OpenBikeshareData: Log In For Admin Access</title>
  </head>
  <body>
    <div class="container">
    <!-- if the login failed varialbe is set, print message that they need to login again -->
    <?php
    if (isset($_GET["login_failed"])) {
      ?> <p>Login failed. Please check your credentials and try again.<br></p> <?php
    }
    ?>
    <!-- form with username and password fields -->
    <h3>Please log in for admin access:</h3>
    <form class="form-horizontal form-validate" role="form" action="/~group2_trips/check-login.php">
      <div class="form-group">
        <label for="username" class="col-sm-2 control-label">Username:</label>
        <div class="col-sm-4">
          <input class="form-control" type="text" value="" name="username" required/>
        </div>
      </div>
      <div class="form-group">
        <label for="password" class="col-sm-2 control-label">Password:</label>
        <div class="col-sm-4">
          <input class="form-control" type="password" value="" name="password" width: 200px; required />
        </div>
      </div>
      <input type="hidden" value="submitted" name="submitted" />
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Log In</button>
        </div>
      </div>
    </form>
  </div>
  </body>
</html>