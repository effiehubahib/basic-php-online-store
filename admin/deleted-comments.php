<?php

include_once("admin_config.php");
include_once("admin_header.php");
	
	$queryComment = "SELECT * FROM tbl_comment WHERE status=-1 ORDER BY id_comment DESC";
	
	$resultComment = mysqli_query($conn,$queryComment);
?>

<div id="product">
<div class="columns-container">
	<div>
		<div class="container">
			<div class="row">
				<div id="center_column" class="center_column col-xs-12 col-sm-12">
					<h1 class="page-heading">List of Deleted Comments</h1>

					<table class="table table-bordered table-hover datatable"> 
						<thead> 
							<tr> 
								<th>ID #</th> 
								<th>Fullname</th> 
								<th>Email</th> 
								<th class="message">Message</th> 
								<th>Status</th> 
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
									<td>
									<?php 
										echo"<a href='comments.php?id=".$comment['id_comment']."&action=unpost'> Set Unpost	</a> |";
										echo"<a href='comments.php?id=".$comment['id_comment']."&action=post'> Post</a>";
										
									?>
									
									</td> 
								</tr>
								<?php
							}
						}else{
							echo"<tr> <th scope='row' colspan='7' style='text-align:center;'>No comments listed yet</th> </tr>"; 
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