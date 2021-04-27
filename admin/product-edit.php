<?php
include_once("admin-config.php");
include_once("admin-header.php");

	$queryBrand = "SELECT * FROM tbl_brand ORDER BY brand_id DESC";
	$resultBrand = mysqli_query($conn,$queryBrand);
	$querySupplier = "SELECT * FROM tbl_supplier ORDER BY supplier_id DESC";
	$resultSupplier = mysqli_query($conn,$querySupplier);


	$error = '';
	if(isset($_POST['saveProduct'])){
		$product_name = $_POST['product_name'];
		$supplier_id = $_POST['supplier_id'];
		$price = $_POST['price'];
		$description = $_POST['description'];
		$quantity = $_POST['quantity'];
		$reorder_level = $_POST['reorder_level'];
		$product_status = $_POST['product_status'];
		$brand_id = $_POST['brand_id'];
		if (empty($product_name) ||  empty($price) ||  is_null($quantity)  ) 
	 	{
	 		$error="Please fill out all the fields";
	 	}elseif(isset($_FILES["product_image"]["size"]) && $_FILES["product_image"]["size"] >0){
	 		$check = getimagesize($_FILES["product_image"]["tmp_name"]);
	 		$mime_list = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/x-windows-bmp');
		   	if(!in_array($check["mime"],$mime_list)){
		   		$error .= "<br/>File is not an image.";
		   	}
		   	if ($_FILES["product_image"]["size"] > 5000000) {
    			$error .= "<br/> Sorry, your file is too large.";
    		}
    		/*directory where to upload product images*/

    		$target_dir = "../uploads/products/";
    		$target_filename = time().".".basename($check["mime"]);
			$target_file = $target_dir .$target_filename;
			

    		if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
		        echo "The file ". basename( $_FILES["product_image"]["name"]). " has been uploaded.";
		    } else {
		       $error .= "<br/>Sorry, there was an error uploading your file.";
		    }
	 	}
	 	
	 	if($error=='')
	 	{

			$query = "UPDATE tbl_product SET product_name = '$product_name', supplier_id = '$supplier_id', brand_id = '$brand_id', price = '$price', description = '$description', reorder_level = '$reorder_level',quantity = '$quantity',product_status = $product_status WHERE product_id = '".$_GET['id']."'";
			$result = mysqli_query($conn,$query);
				if(isset($_FILES["product_image"]["size"]) && $_FILES["product_image"]["size"] >0)
				{
					$query = "UPDATE tbl_product SET product_image = '$target_filename' WHERE product_id = '".$_GET['id']."'";
				}
				$result = mysqli_query($conn,$query);
				if ($result)
				{

					echo "<script>alert('Product successfully updated');window.location='product-edit.php?id=".$_GET['id']."'</script>";
				}
				else
				{
					$error="Error Saving!";
				}
		}	

	}
	if(!isset($_GET['id']))
	{
		echo "<script>alert('Product ID not existing. Page automatically redirect');window.location='products.php'</script>";
	}else{

		$queryProdDisplay = "SELECT * FROM tbl_product WHERE product_id='".$_GET['id']."'";
		$resultProduct = mysqli_query($conn,$queryProdDisplay);
	}
?>

<div id="product-add">
	<div class="container">
		<div id="center_column" class="center_column col-xs-10 col-sm-10">

			<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
				<?php while($product = mysqli_fetch_array($resultProduct))
						{

				?>
				<div class="product_creation">
					<h3 class="page-subheading">Update Product information</h3>
					<p class="text-danger"><?php echo $error;?></p>
					<div class="required form-group">
						<label for="username">Product Name <sup>*</sup></label>
						<input type="text" name="product_name" class="form-control" required="true" value="<?php if(isset($product['product_name'])){ echo $product['product_name'];}?>">
					</div>
					<div class="required form-group">
						<label for="lastname">Brand <sup>*</sup></label>
						<select name="brand_id" class="form-control" required="true">
						<?php
						if($resultBrand->num_rows>0)
						{	
							while($brand=mysqli_fetch_array($resultBrand))
							{
								$selected = '';
								if($brand['brand_id']==$product['brand_id'])
									$selected = "selected = selected";
								echo"<option value='".$brand['brand_id']."' ".$selected.">".$brand['brandname']."</option>";
							}
						}else{
							echo"<option value='0'>No Brand Available</option>"; 
						}
						?>
						</select>
					</div>
					<div class="required form-group">
						<label for="lastname">Supplier <sup>*</sup></label>
						<select name="supplier_id" class="form-control" required="true">
						<?php
						if($resultSupplier->num_rows>0)
						{	
							while($supplier=mysqli_fetch_array($resultSupplier))
							{
								$selected = '';
								if($product['supplier_id']==$supplier['supplier_id'])
									$selected = "selected = selected";
								echo"<option value='".$supplier['supplier_id']."' ".$selected.">".$supplier['suppliername']."</option>";
							}
						}else{
							echo"<option value='0'>No Brand Available</option>"; 
						}
						?>
						</select>
					</div>
					<div class="required form-group">
						<label for="lastname">Price <sup>*</sup></label>
						<input type="text" name="price" class="form-control" required="true" value="<?php if(isset($product['price'])){ echo $product['price'];}?>">
					</div>
					<div class="required form-group">
						<label for="address">Product Description <sup>*</sup></label>
						 <textarea name="description" class="form-control" rows="5" id="description"><?php if(isset($product['description'])){ echo $product['description'];}?></textarea>
					</div>
					<div class="required form-group">
						<label for="email">Reorder Level <sup>*</sup></label>
						<input type="text" name="reorder_level" class="form-control" required="true" value="<?php if(isset($product['reorder_level'])){ echo $product['reorder_level'];}?>">
					</div>
					<div class="required form-group">
						<label for="email">Quantity <sup>*</sup></label>
						<input type="text" name="quantity" class="form-control" required="true" value="<?php if(isset($product['quantity'])){ echo $product['quantity'];}?>">
					</div>
					<div class="required form-group">
						<label for="product_status">Product Status<sup>*</sup></label>
						<input type="radio" name="product_status" value="1" class="product-status" required="true" 
						<?php if(isset($product['product_status']) && $product['product_status']==1 && $product['quantity']!=0){ echo "checked='checked'";}
							if($product['quantity']==0){ echo "disabled='disabled'";}
						?> > Available
						<input type="radio" name="product_status" value="0" class="product-status" required="true" <?php if(isset($product['product_status']) && $product['product_status']==0 || $product['quantity']==0){ echo "checked='checked'";}
						?> >Not Available
					</div>
					<div class="required form-group">
						<label for="email">Upload Product Image <sup>*</sup></label>
						<?php 
							if($product['product_image']!=null) {
								$GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/arome.com/uploads/products/".$product['product_image']."&w=120";
								
							}else{
								$GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/arome.com/uploads/products/no-image.jpg&w=120";
							}
							echo '<div><img border="0" src="'.htmlentities(phpThumbURL($GETstring, $phpThumbBase), ENT_QUOTES).'" alt=""></div>';

						?>
					</div>
					<div class="required form-group">
						<input type="file" name="product_image" class="form-control" >
					</div>	
					<br/>		
				</div>
								
				<div class="submit clearfix">	
					<button type="submit" name="saveProduct" id="saveProduct" class="btn btn-default button button-medium">
						<span>Save Product<i class="icon-chevron-right right"></i></span>
					</button>
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