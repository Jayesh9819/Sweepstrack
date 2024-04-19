<?php
ob_start();

# server name
$sName = "localhost";
# user name
$uName = "sweepstrac";
# password
$pass = "12345678";
$db_name = "sweepstrac";

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
