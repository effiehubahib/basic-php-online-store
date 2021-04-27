<?php

include_once("admin-config.php");
include_once("admin-header.php");

	
	
	if(isset($_GET['id']) && isset($_GET['action']))
	{
		if($_GET['action']=="delete"){
			$queryUserStat = "UPDATE tbl_user SET status=0 WHERE user_id='".$_GET['id']."'";
		}else{
			$queryUserStat = "UPDATE tbl_user SET status=1 WHERE user_id='".$_GET['id']."'";
		}
		$resultUserStat = mysqli_query($conn,$queryUserStat);
	}

	if(isset($_GET['status']) && $_GET['status']==0)
	{
		$queryUser = "SELECT * FROM tbl_user WHERE status = 0 && user_type='Customer' ORDER BY user_id DESC";
	}else{
		$queryUser = "SELECT * FROM tbl_user WHERE status = 1 && user_type='Customer' ORDER BY user_id DESC";
	}
	$resultUser = mysqli_query($conn,$queryUser);
?>

<div id="product">
	<div class="columns-container">
		<div>
			<div class="container">
				<div class="row">
					<div id="center_column" class="center_column col-xs-10 col-sm-10">
						<h3 class="page-heading">List of <?php if(isset($_GET['status']) && $_GET['status']==0) echo "Deleted ";?>Customers 
							</h3>
						<?php 
							if(isset($_GET['id']) && $_GET['action']=="delete")	
							  	echo"<div class='alert alert-danger'><strong>Customer account is deleted!</div>";
							elseif(isset($_GET['action']) && $_GET['action']=="activate")
								echo"<div class='alert alert-success'><strong>Customer account is activated!</div>";
							else
						?>
						<table class="table table-bordered table-hover datatable"> 
							<thead> 
								<tr> 
									<th>Firstname</th> 
									<th>Lastname</th> 
									<th>Email</th> 
									<th>Contact No.</th> 
									<th>Gender</th> 
									<th> Action</th> 
								</tr> 
							</thead> 
							<tbody> 
							<?php
							if($resultUser->num_rows>0)
							{	
								while($customer=mysqli_fetch_array($resultUser))
								{
									?>
									<tr> 
										
										<td><?php echo $customer['firstname'];?></td> 
										<td><?php echo $customer['lastname'];?></td>
										<td><?php echo $customer['email'];?></td>
										<td><?php echo $customer['contact_no'];?></td>
										<td><?php echo $customer['gender'];?></td> 
										<td>
										
										<?php if($customer['status']==1){
											echo"<a href='customers.php?id=".$customer['user_id']."&action=delete'>Delete</a>";
											}else{
											echo"<a href='customers.php?id=".$customer['user_id']."&action=activate'>Activate</a>";
											}
										?>
										</td> 
									</tr>
									<?php
								}
							}else{
								echo"<tr> <th scope='row' colspan='7' style='text-align:center;'>No customers listed yet</th> </tr>"; 
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
<?php 
include_once("admin-footer.php");
?>