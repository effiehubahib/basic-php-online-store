<?php

include_once("admin_config.php");
include_once("admin_header.php");
$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Refund being processed", 
      						 "Refunded", "Order closed");


	$query = "SELECT * FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE deleteStatus=1 ORDER BY id_cart DESC";
	$result = mysqli_query($conn,$query);
?>

<div id="product">
<div class="columns-container">
	<div>
		<div class="container">
			<div class="row">
				<div id="center_column" class="center_column col-xs-12 col-sm-12">
					<h1 class="page-heading">List of Deleted Orders 
						</h1>


					<table class="table table-bordered table-hover datatable"> 
						<thead> 
							<tr> 
								<th>Reference #</th> 
								<th>Customer Name</th> 
								<th>Status</th> 
								<th>Ordered Date</th> 
								<th> Action</th> 
							</tr> 
						</thead> 
						<tbody> 
						<?php
						if($result->num_rows>0)
						{	
							while($order=mysqli_fetch_array($result))
							{
								?>
								<tr> 
									<th scope="row"><?php echo date("Ymd",strtotime($order['dateadded']))."-".$order['id_cart'];?></th> 
									
									<td><?php echo $order['lastname'].",".$order['firstname'];?></td> 
									<td><?php echo $deliverystatus[$order['delivery_status']];?></td> 
									<td><?php echo date("F d, Y h:i a",strtotime($order['dateadded']));?></td> 
									<td>
									<a href="view-customer-order.php?id=<?php echo $order['id_cart'];?>"> <i class="fa fa-edit" aria-hidden="true"></i>View Order</a> | 
									<a href='orders.php?id=<?php echo $order['id_cart'];?>&action=restore'> <i class='fa fa-check-circle' aria-hidden='true'></i> Restore</a>
									</td> 
								</tr>
								<?php
							}
						}else{
							echo"<tr> <th scope='row' colspan='5'>No categories yet</th> </tr>"; 
						}
						?>
						
						</tbody> 
					</table>

				</div>
			</div><!-- #center_column -->
		</div>

	</div>
</div>
</div>