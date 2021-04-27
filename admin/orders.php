<?php

include_once("admin-config.php");
include_once("admin-header.php");


if(isset($_GET['id']) && isset($_GET['action']))
{
	if($_GET['action']=="delete"){
		$queryOrderStat = "UPDATE tbl_cart SET deleteStatus=1 WHERE cart_id='".$_GET['id']."'";
	}else{
		$queryOrderStat = "UPDATE tbl_cart SET deleteStatus=0 WHERE cart_id='".$_GET['id']."'";
	}

	$resultOrderStat = mysqli_query($conn,$queryOrderStat);
}


	$query = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.user_id= tbl_user.user_id WHERE deleteStatus=0 AND delivery_status!= 7 ORDER BY cart_id DESC";
	$result = mysqli_query($conn,$query);
?>

<div id="orders">
	<div class="container">
		<div class="row">
			<div id="center_column" class="center_column col-xs-10 col-sm-10">
				<h3 class="page-heading">List of Orders </h3>


				<table class="table table-bordered table-hover ordertable"> 
					<thead> 
						<tr> 
							<th>Order #</th> 
							<th>Customer Name</th> 
							<th>Status</th> 
							<th>Ordered Date</th> 
							<th> Action</th> 
						</tr> 
					</thead> 
					<tbody> 
					<?php
					if(mysqli_num_rows($result)>0)
					{	
						while($order=mysqli_fetch_array($result))
						{
							?>
							<tr> 
								<th scope="row"><?php echo $order['cart_id'];?></th> 
								<td><?php echo $order['lastname'].",".$order['firstname'];?></td> 
								<td><?php echo $order['delivery_status'];?></td> 
								<td><?php echo date("F d, Y h:i a",strtotime($order['dateadded']));?></td> 
								<td>
								<a href="view-customer-order.php?id=<?php echo $order['cart_id'];?>"> <i class="fa fa-edit" aria-hidden="true"></i>View Order</a>
								<?php if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"]=="Staff")){?>
								 | <a href='orders.php?id=<?php echo $order['cart_id'];?>&action=delete'> <i class='fa fa-times' aria-hidden='true'></i> Delete</a>
								<?php } ?>
								</td> 
							</tr>
							<?php
						}
					}else{
						echo"<tr> <th scope='row' colspan='5'>No orders yet</th> </tr>"; 
					}
					?>
					
					</tbody> 
				</table>

			</div>
		</div><!-- #center_column -->
	</div>

</div>
<?php
include_once("admin-footer.php");
?>