<?php

include_once("admin-config.php");

$products = array();

if(isset($_GET['supplier_id']))
{
	$query = "SELECT * FROM tbl_product  WHERE supplier_id=".$_GET['supplier_id']." AND quantity<=reorder_level";

	$result = mysqli_query($conn,$query);
	if(mysqli_num_rows($result)>0){
		while($row = mysqli_fetch_array($result)){
			$products[$row['product_id']]['product_name'] = $row['product_name'];
			$products[$row['product_id']]['price'] = $row['price'];
				
		}
	}
}

echo json_encode($products);