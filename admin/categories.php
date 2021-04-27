<?php


include_once("admin_config.php");
if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"]!=3)){
		header("Location: index.php"); 
}
include_once("admin_header.php");


	
	
	if(isset($_GET['id']) && isset($_GET['action']) && $_GET['action']=='delete')
	{
		$queryCatDelete = "DELETE FROM tbl_category WHERE id_category='".htmlentities($_GET['id'])."'";
		$resultProduct = mysqli_query($conn,$queryCatDelete);
	}
	if(isset($_GET['id']) && isset($_GET['cat_status']))
	{
		$queryCatUpdate = "UPDATE tbl_category SET category_status=$_GET[cat_status] WHERE id_category='".$_GET['id']."'";
		$resultCat = mysqli_query($conn,$queryCatUpdate);
	}

	$query = "SELECT * FROM tbl_category ORDER BY id_category DESC";
	$result = mysqli_query($conn,$query);
?>

<div id="product">
<div class="columns-container">
	<div>
		<div class="container">
			<div class="row">
				<div id="center_column" class="center_column col-xs-12 col-sm-12">
					<h1 class="page-heading">List of Categories <span class="pull-right btn btn-primary"> 
						<a href="category-add.php"> <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New </a></span>
						</h1>


					<table class="table table-bordered table-hover datatable"> 
						<thead> 
							<tr> 
								<th>ID #</th> 
								<th>Category Name</th> 
								<th>No. of Products Quantity</th> 
								<th> Action</th> 
							</tr> 
						</thead> 
						<tbody> 
						<?php
						if($result->num_rows>0)
						{	
							while($category=mysqli_fetch_array($result))
							{
									$queryCount = "SELECT SUM(quantity) as totalprod FROM tbl_product WHERE id_category='".$category['id_category']."' GROUP BY id_category";

									$resultCount = mysqli_query($conn,$queryCount);

								?>
								<tr> 
									<th scope="row"><?php echo $category['id_category'];?></th> 
									<td><?php echo $category['categoryname'];?></td> 
									<td>
										<?php if(mysqli_num_rows($resultCount)>0){
												while($categoryRow=mysqli_fetch_array($resultCount)){
													
												  echo $categoryRow['totalprod'];
												}
											}else{echo"0";}
										?>
									</td>
									
									<td>
									<a href="category-edit.php?id=<?php echo $category['id_category'];?>"> <i class="fa fa-edit" aria-hidden="true"></i>Update</a> | 
									<a href="categories.php?action=delete&id=<?php echo $category['id_category'];?>"> <i class="fa fa-times" aria-hidden="true"></i> Delete</a> |
									<?php if($category['category_status']==1)
											{
												$stat = "Display";
												$change = 0;
											}else{
												$stat = "No Display";
												$change = 1;
											}
									?>
									<a href="categories.php?id=<?php echo $category['id_category'];?>&cat_status=<?php echo $change;?>"> <i class="fa fa-star" aria-hidden="true"></i> 
									  <?php echo $stat;?>
									</a>
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