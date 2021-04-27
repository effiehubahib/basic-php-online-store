<?php
include_once("admin-config.php");
include_once("admin-header.php");

	$queryBrand = "SELECT * FROM tbl_brand ORDER BY brand_id DESC";
	$resultBrand = mysqli_query($conn,$queryBrand);
	$querySupplier = "SELECT * FROM tbl_supplier ORDER BY supplier_id DESC";
	$resultSupplier = mysqli_query($conn,$querySupplier);


	$error = '';
	if(isset($_POST['saveNewStock'])){
		
		$addstock = $_POST['addstock'];
		$supplier_id = $_POST['supplier_id'];
		$product_id = $_POST['product_id'];
		if (is_null($addstock)  ) 
	 	{
	 		$error="Please fill out stock quantity";
	 	}
	 	
	 	if($error=='')
	 	{

			$query = "UPDATE tbl_product SET quantity = quantity +'$addstock' WHERE product_id = '".$_GET['product_id']."'";
			$result = mysqli_query($conn,$query);
			if ($result)
			{
				$date_added = date("Y-m-d H:i:s");
				$queryAddStock = "INSERT INTO tbl_addedstock(product_id, supplier_id, date_added, stockquantity,addedby) VALUES('$product_id', '$supplier_id','$date_added','$addstock','".$_SESSION["user_id"]."')";
				$resultStock = mysqli_query($conn,$queryAddStock);

				echo "<script>alert('Product stock successfully updated');window.location='product-edit.php?id=".$_GET['product_id']."'</script>";
			}
			else
			{
				$error="Error Saving!";
			}
		}	

	}
	if(!isset($_GET['product_id']))
	{
		echo "<script>alert('Product ID not existing. Page automatically redirect');window.location='reorder_level.php'</script>";
	}else{

		$queryProdDisplay = "SELECT * FROM tbl_product LEFT JOIN tbl_supplier ON tbl_product.supplier_id = tbl_supplier.supplier_id LEFT JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id WHERE product_id='".$_GET['product_id']."'";
		$resultProduct = mysqli_query($conn,$queryProdDisplay);
	}
?>

<div id="product-add">
	<div class="container">
		<div id="center_column" class="center_column col-xs-10 col-sm-10">
			<h3 class="page-subheading">Add Stock</h3>
			<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
				<?php while($product = mysqli_fetch_array($resultProduct))
						{

				?>
				<div class="col-md-6 col-xs-10 col-sm-10">
					<?php 
						if($product['product_image']!=null) {
							$GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/arome.com/uploads/products/".$product['product_image']."&w=420";
							
						}else{
							$GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/arome.com/uploads/products/no-image.jpg&w=420";
						}
						echo '<div><img border="0" src="'.htmlentities(phpThumbURL($GETstring, $phpThumbBase), ENT_QUOTES).'" alt=""></div>';

					?>
				</div>
				<div class="col-md-6 col-xs-10 col-sm-10">
						<p > Product Name: <?php echo $product['product_name'];?></p>
						<p > Supplier Name:<?php echo $product['suppliername'];?></p>
						<p > Brand: <?php echo $product['brandname'];?></p>
						<p > Made in: <?php echo $product['made'];?></p>
						<p > Price: <?php echo $product['price'];?></p>
						<p > Current Quantity: <?php echo $product['quantity'];?></p>
						<div class="product_creation">
							<div class="required form-group">
								<label for="email">Stock Quantity <sup>*</sup></label>
								<input type="text" name="addstock" class="form-control" required="true">
								<input type="hidden" name="product_id" value="<?php echo $product['product_id'];?>">
								<input type="hidden" name="supplier_id" value="<?php echo $product['supplier_id'];?>">
							</div>	
						</div>
						<div class="submit clearfix">	
							<button type="submit" name="saveNewStock" id="saveNewStock" class="btn btn-default button button-medium">
								<span>Save New Stock<i class="icon-chevron-right right"></i></span>
							</button>
						</div>
				</div>
				
								
				
				<?php
				}
				?>
			</form>
		</div><!-- #center_column -->
		
	</div>

</div>
<?php 
include_once("admin-footer.php");
?>