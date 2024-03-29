<?php
ob_start();

# server name
$sName = "193.203.184.53";
# user name
$uName = "u306273205_cc";
# password
$pass = "Weblux@@1122";

# database name
$db_name = "u306273205_cc";

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
