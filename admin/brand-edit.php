<?php

include_once("admin-config.php");
if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"]=="Customer")){
		header("Location: index.php"); 
}
include_once("admin-header.php");


	$error = '';
	if(isset($_POST['editBrand'])){

		

		$brandname = $_POST['brandname'];
		$brand_status = $_POST['brand_status'];
		if (empty($brandname) )
		{
	 		$error="Please fill out the field";
	 	}
	 	
	 	if($error=='')
	 	{

			$query = "UPDATE tbl_brand SET brandname = '$brandname', brand_status ='$brand_status' WHERE brand_id = '".$_GET['id']."'";
			$result = mysqli_query($conn,$query);
			if ($result)
			{
				echo "<script>alert('Brand successfully updated');window.location='brand-edit.php?id=".$_GET['id']."'</script>";
			}
			else
			{
				$error="Error Saving!";
			}
		}	

	}

	if(!isset($_GET['id']))
	{
		echo "<script>alert('Brand ID not existing. Page automatically redirect');window.location='brands.php'</script>";
	}else{

		$queryBrandDisplay = "SELECT * FROM tbl_brand WHERE brand_id='".htmlentities($_GET['id'])."'";
		$resultBrand = mysqli_query($conn,$queryBrandDisplay);
	}
?>

<div id="brand-edit">
<div class="columns-container">
	<div>
		<div class="container">
			<div id="center_column" class="center_column col-xs-10 col-sm-10">
				<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
				<?php while($brand = mysqli_fetch_array($resultBrand))
						{

				?>
					<div class="brand_creation">
						<h3 class="page-subheading">Update Brand information</h3>
						<p class="text-danger"><?php echo $error;?></p>
						<div class="required form-group">
							<label for="category_name">Brand Name </label>
							<input type="text" name="brandname" class="form-control" required="true" value="<?php if(isset($brand['brandname'])){ echo $brand['brandname'];}?>">
							</div>	
							<div class="required form-group">
							<label for="product_status">Brand Status </label>
							<input type="radio" name="brand_status" value="1" class="brand-status" required="true" <?php if(isset($brand['brand_status']) && $brand['brand_status']==1){ echo "checked='checked'";}?> > Available
							<input type="radio" name="brand_status" value="0" class="brand-status" required="true" <?php if(isset($brand['brand_status']) && $brand['brand_status']==0){ echo "checked='checked'";}?> >Not Available
						</div>
					</div>
									
					<div class="submit clearfix">	
						<button type="submit" name="editBrand" id="editBrand" class="btn btn-default button button-medium">
							<span>Save Brand<i class="icon-chevron-right right"></i></span>
						</button>
					</div>
				<?php
					}
				?>
				</form>
				</div><!-- #center_column -->
			</div>

		</div>
	</div>
</div>
</div>
<?php
include_once("admin-footer.php");
?>