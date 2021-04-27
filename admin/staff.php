<?php

include_once("admin-config.php");
include_once("admin-header.php");

	
	
	if(isset($_GET['user_id']) && isset($_GET['action']))
	{
		if($_GET['action']=="deactivate"){
			$queryAdmin = "UPDATE tbl_user SET status=0 WHERE user_id='".$_GET['user_id']."'";
		}elseif($_GET['action']=="delete"){
			$queryAdmin = "UPDATE tbl_user SET status=-1 WHERE user_id='".$_GET['user_id']."'";
		}else{
			$queryAdmin = "UPDATE tbl_user SET status=1 WHERE user_id='".$_GET['user_id']."'";
		}
		$resultAdmin = mysqli_query($conn,$queryAdmin);
	}


	if(isset($_GET['status']) && $_GET['status']==0)
	{
		$queryUser = "SELECT * FROM tbl_user WHERE status = 0 && user_type='Staff' ORDER BY user_id DESC";
	}elseif(isset($_GET['status']) && $_GET['status']==-1){
		$queryUser = "SELECT * FROM tbl_user WHERE status = -1 && user_type='Staff' ORDER BY user_id DESC";

	}else{
		$queryUser = "SELECT * FROM tbl_user WHERE status = 1 && user_type='Staff' ORDER BY user_id DESC";

	}

	$resultUser = mysqli_query($conn,$queryUser);
?>

<div id="staff">

	<div id="center_column" class="center_column col-xs-10 col-sm-12">
		<h3 class="page-heading">List of 
			<?php if(isset($_GET['status']))
			{	
				if($_GET['status']==-1){ 
					echo "Deleted ";
				}elseif($_GET['status']==0){
					echo "Inactive";
				}else{
					echo "Active";
				}
			}
			?>
				Staff
		</h3>

		<?php 
			if(isset($_GET['id']) && $_GET['action']=="delete")	
			  	echo"<div class='alert alert-danger'><strong>Staff account is deleted!</div>";
			elseif(isset($_GET['action']) && $_GET['action']=="activate")
				echo"<div class='alert alert-success'><strong>Staff account is activated!</div>";
			else
		?>
		<table class="table table-bordered table-hover datatable"> 
			<thead> 
				<tr> 
					<th>Username</th> 
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
				while($staff=mysqli_fetch_array($resultUser))
				{
					?>
					<tr> 
						<td><?php echo $staff['username'];?></td> 
						<td><?php echo $staff['firstname'];?></td> 
						<td><?php echo $staff['lastname'];?></td>
						<td><?php echo $staff['email'];?></td>
						<td><?php echo $staff['contact_no'];?></td>
						<td><?php echo $staff['gender'];?></td> 
						<td>
						<a href="staff-update.php?id=<?php echo $staff['user_id'];?>"> Update</a> | 
						<?php if($staff['status']==1){
							echo"<a href='staff.php?user_id=".$staff['user_id']."&action=deactivate'> Deactivate </a>";
							}else{
							echo"<a href='staff.php?user_id=".$staff['user_id']."&action=activate'> Activate </a>";
							}
							if($staff['status']!=-1){
								echo" | <a href='staff.php?user_id=".$staff['user_id']."&action=delete'> Delete </a>";
							}
						?>

						</td> 
					</tr>
					<?php
				}
			}else{
				echo"<tr> <th scope='row' colspan='8' style='text-align:center;'>No staff records</th> </tr>"; 
			}
			?>
			
			</tbody> 
		</table>

	</div>

</div>

<?php
include_once("admin-footer.php");
?>