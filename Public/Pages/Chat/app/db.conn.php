<?php
ob_start();

# server name
$sName = "199.231.187.147";
# user name
$uName = "sweeps_trac";
# password
$pass = "Weblux@@1122";

# database name
$db_name = "sweeps_trac";

#creating database connection
try {
  $conn = new PDO(
    "mysql:host=$sName;dbname=$db_name",
    $uName,
    $pass
  );
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed : " . $e->getMessage();
}
