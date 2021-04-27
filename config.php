<?php
$host = "localhost";
$db_name = "db_ordering";
$username = "root";
$password = "";
 
$conn = mysqli_connect($host, $username, $password,$db_name);
 
if (!$conn) {
	echo"failed";
    die("Connection failed: " . mysqli_connect_error());
}

$deliverystatus=array(	
      					"For delivery", 
      					"Delivered", 
      				);
$purchase_order_status=array(	
      					"Pending", 
      					"Delivered", 
      				);
      				
?>