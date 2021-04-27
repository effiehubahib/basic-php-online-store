<?php

include_once("inc_header.php");
if(!isset($_SESSION["user_type"]) || !isset($_SESSION["user_id"])){
	header("Location:login.php"); 
}


	$query = "SELECT * FROM tbl_delivery WHERE user_id = '".$_SESSION['user_id']."'";
	$result = mysqli_query($conn,$query);

$deliveryQuery = "SELECT value FROM tbl_setting WHERE id=1";

$resultDeliveryFee = mysqli_query($conn,$deliveryQuery);
$deliveryfee=0;
while($rowDelivery=mysqli_fetch_array($resultDeliveryFee)){
	$deliveryfee = $rowDelivery['value'];
}


/***** get current city *****/

$queryUser = "SELECT * FROM tbl_user WHERE user_id = '".$_SESSION['user_id']."'";
$resultUser = mysqli_query($conn,$queryUser);
while($rowUser=mysqli_fetch_array($resultUser)){
	$address= $rowUser['address'];
}

?>
<div id="page">
	<div class="columns-container">
		<div id="columns" class="container">
			<div class="row">
				<div id="center_column" class="center_column col-xs-12 col-sm-12">
					
<?php
		$cookie = isset($_COOKIE['cart_items_cookie']) ?$_COOKIE['cart_items_cookie']: "";
        $cookie = stripslashes($cookie);
        $saved_cart_items = json_decode($cookie, true);
         
        if(count($saved_cart_items)>0){
            
            $ids = "";
            foreach($saved_cart_items as $id=>$name){
                $ids = $ids . $id . ",";
            }
            // remove the last comma
            $ids = rtrim($ids, ',');
         
            //start table

            echo "<h1 class='page-heading'>Your Order</h1>
            		<table class='table table-hover table-responsive table-bordered'>";
         
                // our table heading
                echo "<tr>";
                    echo "<th class='textAlignCenter'>Product Name</th>";
                    echo "<th class='textAlignCenter'>Quantity</th>";
                    echo "<th class='textAlignCenter'>Unit Price</th>";
                    echo "<th class='textAlignCenter'>Total Price</th>";
                echo "</tr>";
         
                $query = "SELECT product_id, product_name, price FROM tbl_product WHERE product_id IN ({$ids}) ORDER BY product_name";
                $result = mysqli_query($conn,$query);
         
                $total_price=0;
               

                while($row=mysqli_fetch_array($result)){
         
                    echo "<tr>";
                        echo "<td>{$row['product_name']}</td>";
                        echo "<td>{$saved_cart_items[$row['product_id']]['quantity']} </td>";
                        echo "<td>".number_format($saved_cart_items[$row['product_id']]['price'],2)."</td>";
                         echo "<td class='textAlignRight'>".number_format($saved_cart_items[$row['product_id']]['quantity'] * $saved_cart_items[$row['product_id']]['price'],2)."</td>";
                    echo "</tr>";
         
                    $total_price+= ($saved_cart_items[$row['product_id']]['quantity'] * $saved_cart_items[$row['product_id']]['price']);
                }
               
                echo "<tr>";
                        echo "<td class='total' ><b>Overall Total</b></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "<td class='total textAlignRight tdTotal' data-total='".$total_price."'>PHP <span id='totalFee'>".number_format($total_price,2)."</span></td>";
                    echo "</tr>";
         
            echo "</table>";
        }
 ?>

						<div class="col-xs-12 col-sm-6">
							<form action="submit-order.php" method="POST" id="login_form" class="box address_delivery" enctype="multipart/form-data">
								<input type="hidden" id="inputDeliveryFee" name="inputDeliveryFee" value="<?php echo $deliveryfee;?>">
								<div class="form_content clearfix">
									
									<div class="form-group">
										<h2>Check and confirm delivery address</h2>
										<input class="form-control" type="text" required="true" id="addressname" name="addressname" value="<?php echo $address;?>" placeholder="Complete Address">
									</div>
									
									<p class="submit">
									
									<button type="submit" id="submitOrder" name="submitOrder" > Confirm Order </button>
									</p>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

