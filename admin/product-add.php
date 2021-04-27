<?php

include_once("admin-config.php");
include_once("admin-header.php");

	$queryBrand = "SELECT * FROM tbl_brand ORDER BY brand_id DESC";
	$resultBrand = mysqli_query($conn,$queryBrand);
	$querySize = "SELECT * FROM tbl_size ORDER BY size_id DESC";
	$resultSize = mysqli_query($conn,$querySize);
	$querySupplier = "SELECT * FROM tbl_supplier ORDER BY supplier_id DESC";
	$resultSupplier = mysqli_query($conn,$querySupplier);
	$error = '';
	if(isset($_POST['addProduct'])){

		

		$product_name = $_POST['product_name'];
		$price = $_POST['price'];
		$reorder_level = $_POST['reorder_level'];
		$description = $_POST['description'];
		$quantity = $_POST['quantity'];
		$product_status = $_POST['product_status'];
		$brand_id = $_POST['brand_id'];
		$supplier_id = $_POST['supplier_id'];
		$size = $_POST['size'];
		$product_type = $_POST['product_type'];
		$made  = $_POST['made'];
		$target_filename = NULL;
		if (empty($product_name) ||  empty($price) ||  empty($quantity) || empty($product_status) )
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

			$query = "INSERT INTO tbl_product( product_name, supplier_id, price, description, quantity, product_status,product_image,brand_id,reorder_level,size,product_type,made) 
					VALUES('$product_name','$supplier_id','$price','$description','$quantity',$product_status,'$target_filename','$brand_id','$reorder_level','$size','$product_type','$made')";

			$result = mysqli_query($conn,$query);
			if ($result)
			{
				echo "<script>alert('Product successfully created');window.location='products.php'</script>";
			}
			else
			{
				$error="Error Saving!";
			}
		}	

	}
?>

<div id="product-add">
 <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small> Add New Product </small>
                </h1>
            </div>
        </div>
		<div class="columns-container">
					<div>
						<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
		
							<div class="account_creation">
								<p class="text-danger"><?php echo $error;?></p>
								<div class="required form-group">
									<label for="username">Product Name <sup>*</sup></label>
									<input type="text" name="product_name" class="form-control" required="true" value="<?php if(isset($_POST['product_name'])){ echo $_POST['product_name'];}?>">
								</div>
								<div class="required form-group">
									<label for="lastname">Brand <sup>*</sup></label>
									<select name="brand_id" class="form-control" required="true">
									<?php
									if($resultBrand->num_rows>0)
									{	

										echo"<option value=''>Select Product Brand</option>";
										while($brand=mysqli_fetch_array($resultBrand))
										{
											echo"<option value='".$brand['brand_id']."'>".$brand['brandname']."</option>";
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
									{	echo"<option value=''>Select Product Supplier</option>";
										while($supplier=mysqli_fetch_array($resultSupplier))
										{
											echo"<option value='".$supplier['supplier_id']."'>".$supplier['suppliername']."</option>";
										}
									}else{
										echo"<option value='0'>No Supplier Available</option>"; 
									}
									?>
									</select>
								</div>
								<div class="required form-group">
									<label for="lastname">Product Type <sup>*</sup></label>
									<select name="product_type" class="form-control" required="true">
										<option value=''>Select Product Type</option>
										<option value='Tubeless'>Tubeless</option>
										<option value='With Tube'>With Tube</option>
									</select>
								</div>
								<div class="required form-group">
									<label for="lastname">Product Size <sup>*</sup></label>
									<select name="size" class="form-control" required="true">
										<?php
										if($resultSize->num_rows>0)
										{	
											while($size=mysqli_fetch_array($resultSize))
											{
												echo"<option value=''>Select Product Size</option>";
												echo"<option value='".$size['sizename']."'>".$size['sizename']."</option>";
											}
										}else{
											echo"<option value=''>No Size Available</option>"; 
										}
										?>
									</select>
								</div>
								<div class="required form-group">
									<label for="lastname">Product Made <sup>*</sup></label>
									<select name="made" class="form-control" required="true">
										<option value=''>Select Product Made</option>
										<option value='China'>China</option>
										<option value='Japan'>Japan</option>
										<option value='Philippines'>Philippines</option>
										<option value='Taiwan'>Taiwan</option>
									</select>
								</div>
								<div class="required form-group">
									<label for="lastname">Price <sup>*</sup></label>
									<input type="text" name="price" class="form-control" required="true" value="<?php if(isset($_POST['price'])){ echo $_POST['price'];}?>">
								</div>
								<div class="required form-group">
									<label for="address">Product Description <sup>*</sup></label>
									 <textarea name="description" class="form-control" rows="5" id="description"><?php if(isset($_POST['description'])){ echo $_POST['description'];}?></textarea>
								</div>
								<div class="required form-group">
									<label for="email">Reorder Level <sup>*</sup></label>
									<input type="text" name="reorder_level" class="form-control" required="true" value="<?php if(isset($_POST['reorder_level'])){ echo $_POST['reorder_level'];}?>">
								</div>
								<div class="required form-group">
									<label for="email">Quantity <sup>*</sup></label>
									<input type="text" name="quantity" class="form-control" required="true" value="<?php if(isset($_POST['quantity'])){ echo $_POST['quantity'];}?>">
								</div>
								<div class="required form-group product-status">
									<label for="contact_no">Product Status<sup>*</sup></label>
									<input type="radio" name="product_status" value="1" class="form-control" required="true" <?php if(isset($_POST['product_status']) && $_POST['product_status']==1){ echo "selected='selected'";}?> > Available
									<input type="radio" name="product_status" value="0" class="form-control" required="true" <?php if(isset($_POST['product_status']) && $_POST['product_status']==0){ echo "selected='selected'";}?> >Not Available
								</div>
								<div class="required form-group">
									<label for="email">Upload Product Image <sup>*</sup></label>
									<input type="file" name="product_image" class="form-control" >
								</div>	
								<br/>		
							</div>
											
							<div class="submit clearfix">	
								<button type="submit" name="addProduct" id="addProduct" class="btn btn-default button button-medium">
									<span>Add Product<i class="icon-chevron-right right"></i></span>
								</button>
							</div>
						</form>
					</div>
		</div>
	</div>
 </div>
</div>
<?php
include_once("admin-footer.php");
?>