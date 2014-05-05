<?php
session_start();
if (!isset($_SESSION["access_level"])) {
  header('Location: http://holden.ischool.utexas.edu/~group2_trips/login.php');
  exit;
} 
if ($page_type == "superuser" && $_SESSION["access_level"] != "superuser") {
  header('Location: http://holden.ischool.utexas.edu/~group2_trips/login.php');
  exit;
}
if ($page_type == "admin" && $_SESSION["access_level"] != "superuser" && $_SESSION["access_level"] != "admin") {
  header('Location: http://holden.ischool.utexas.edu/~group2_trips/login.php');
  exit;
}