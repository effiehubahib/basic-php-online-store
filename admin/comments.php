<?php

include_once("admin_config.php");
include_once("admin_header.php");

	
	
	if(isset($_GET['id']) && isset($_GET['action']))
	{
		if($_GET['action']=="delete"){
			$queryCommentStat = "UPDATE tbl_comment SET status=-1 WHERE id_comment='".$_GET['id']."'";
		}elseif($_GET['action']=="post"){
			$queryCommentStat = "UPDATE tbl_comment SET status=1 WHERE id_comment='".$_GET['id']."'";
		}else{
			$queryCommentStat = "UPDATE tbl_comment SET status=0 WHERE id_comment='".$_GET['id']."'";
		}
		$resultUserStat = mysqli_query($conn,$queryCommentStat);
	}


	
	$queryComment = "SELECT * FROM tbl_comment WHERE status!=-1 ORDER BY id_comment DESC";
	
	$resultComment = mysqli_query($conn,$queryComment);
?>

<div id="product">
<div class="columns-container">
	<div>
		<div class="container">
			<div class="row">
				<div id="center_column" class="center_column col-xs-12 col-sm-12">
					<h1 class="page-heading">List of Comments</h1>
					<?php 
						if(isset($_GET['id']) && $_GET['action']=="delete")	
						  	echo"<div class='alert alert-danger'><strong>Customer comment #: ".$_GET['id']." is deleted!</div>";
						elseif(isset($_GET['action']) && $_GET['action']=="post")
							echo"<div class='alert alert-success'><strong>Customer comment #: ".$_GET['id']." is posted!</div>";
						elseif(isset($_GET['id']) && $_GET['action']=="unpost")
							echo"<div class='alert alert-success'><strong>Comment comment  #: ".$_GET['id']." is inactive!</div>";
					?>

					<table class="table table-bordered table-hover datatable"> 
						<thead> 
							<tr> 
								<th>ID #</th> 
								<th>Fullname</th> 
								<th>Email</th> 
								<th class="message">Message</th> 
								<th>Status</th> 
								<th>Date Created</th> 
								<th> Action</th> 
							</tr> 
						</thead> 
						<tbody> 
						<?php
						if($resultComment->num_rows>0)
						{	
							while($comment=mysqli_fetch_array($resultComment))
							{
								?>
								<tr> 
									<th scope="row"><?php echo $comment['id_comment'];?></th> 
									
									<td><?php echo $comment['fullname'];?></td> 
									<td><?php echo $comment['email'];?></td>
									<td><?php echo $comment['message'];?></td>
									<td><?php 
											if($comment['status']==0)
												echo "Inactive";
											elseif($comment['status']==1)
												echo"Posted";
											else
												echo "Deleted";
										?>
										
									</td>
									<td><?php echo date("M d, Y",strtotime($comment['date_created']));?></td>
									<td>
									<?php if($comment['status']==1){
										echo"<a href='comments.php?id=".$comment['id_comment']."&action=unpost'> Unpost</a> |";
										}else{
										echo"<a href='comments.php?id=".$comment['id_comment']."&action=post'> Post</a> | ";
										}
										echo "<a href='comments.php?id=".$comment['id_comment']."&action=delete'> Delete</a>";
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