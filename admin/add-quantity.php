<?php

include_once("admin_config.php");
include_once("admin_header.php");

	$queryCategory = "SELECT * FROM tbl_category ORDER BY id_category DESC";
	$resultCategory = mysqli_query($conn,$queryCategory);

	$error = '';
	if(isset($_POST['addQuantity'])){

		
		$quantity = $_POST['quantity'];
		$query = "UPDATE tbl_product SET  quantity = quantity +'$quantity' WHERE id_product = '".$_GET['id']."'";
		$result = mysqli_query($conn,$query);
				
		if ($result)
		{

			echo "<script>alert('Product quantity successfully updated');window.location='products.php'</script>";
		}
		else
		{
			$error="Error Saving!";
		}
	}	

	if(!isset($_GET['id']))
	{
		echo "<script>alert('Product ID not existing. Page automatically redirect');window.location='products.php'</script>";
	}else{

		$queryProdDisplay = "SELECT * FROM tbl_product WHERE id_product='".htmlentities($_GET['id'])."'";
		$resultProduct = mysqli_query($conn,$queryProdDisplay);
	}
?>

<div id="product-add">
<div class="columns-container">
			<div>
				<div class="container">
					<div class="row">
						<div id="center_column" class="center_column col-xs-12 col-sm-12">

						<h1 class="page-heading">Add Product Quantity</h1>
						<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
						<?php while($product = mysqli_fetch_array($resultProduct))
								{

						?>
							<div class="account_creation">
								<h3 class="page-subheading">Product information</h3>
								<p class="text-danger"><?php echo $error;?></p>
								<div class="required form-group">
									<label for="username">Product Name</label>
									<?php echo $product['product_name']; ?>
								</div>
								
								
								<div class="required form-group">
									<?php echo $product['description'];?>
								</div>
								<div class="required form-group">
									<?php echo "Current Quantity:".$product['quantity'];?>
								</div>
								<div class="required form-group">
									<label for="email">Enter Quantity <sup>*</sup></label>
									<input type="text" name="quantity" class="form-control" required="true" value="">
								</div>
								
							</div>
							<input type="hidden" name="currentQuantity" value="<?php echo $product['quantity'];?>">		
							<div class="submit clearfix">	
								<button type="submit" name="addQuantity" id="addQuantity" class="btn btn-default button button-medium">
									<span>Add Quantity<i class="icon-chevron-right right"></i></span>
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