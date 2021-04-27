<?php

include_once("admin-config.php");
include_once("admin-header.php");


if(isset($_GET['id']) && isset($_GET['action']))
{
	if($_GET['action']=="delete"){
		$queryPOrderStat = "UPDATE tbl_purchase_order SET status='Deleted' WHERE po_id='".$_GET['id']."'";
	}

	$resultPOrderStat = mysqli_query($conn,$queryPOrderStat);
}


	$query = "SELECT * FROM tbl_purchase_order po LEFT JOIN tbl_supplier s ON po.supplier_id = s.supplier_id ORDER BY po_id DESC";
	$result = mysqli_query($conn,$query);
?>

<div id="orders">
	<div class="container">
		<div class="row">
			<div id="center_column" class="center_column col-xs-10 col-sm-10">
				<h3 class="page-heading">List of Supplier Purchase Orders </h3>


				<table class="table table-bordered table-hover ordertable"> 
					<thead> 
						<tr> 
							<th>P. Order #</th> 
							<th>Supplier</th> 
							<th>Ordered Date</th> 
							<th>Status</th> 
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
								<th scope="row"><?php echo $order['po_id'];?></th> 
								<td><?php echo $order['suppliername'];?></td> 
								<td><?php echo date("F d, Y h:i a",strtotime($order['datecreated']));?></td> 

								<td><?php echo $order['status'];?></td> 
								<td>
								<a href="view-supplier-order.php?id=<?php echo $order['po_id'];?>"> <i class="fa fa-edit" aria-hidden="true"></i>View Order</a>
								
								 | <a href='supplier-purchase-order.php?id=<?php echo $order['po_id'];?>&action=delete'> <i class='fa fa-times' aria-hidden='true'></i> Delete</a>
								
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