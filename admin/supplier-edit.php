<?php
include_once("admin-config.php");
include_once("admin-header.php");

	$queryBrand = "SELECT * FROM tbl_brand ORDER BY brand_id DESC";
	$resultBrand = mysqli_query($conn,$queryBrand);

	$error = '';
	if(isset($_POST['saveSupplier'])){
		$suppliername = $_POST['suppliername'];
		$contactname = $_POST['contactname'];
		$contactnumber = $_POST['contactnumber'];
		$address = $_POST['address'];
		if (empty($suppliername)) 
	 	{
	 		$error="Please fill out all the fields";
	 	}
	 	
	 	if($error=='')
	 	{

			$query = "UPDATE tbl_supplier SET suppliername = '$suppliername', contactname = '$contactname', contactnumber = '$contactnumber', address = '$address' WHERE supplier_id = '".$_GET['id']."'";
			$result = mysqli_query($conn,$query);
			
			if ($result)
			{

				echo "<script>alert('Supplier successfully updated');window.location='suppliers.php'</script>";
			}
			else
			{
				$error="Error Saving!";
			}
		}	

	}
	if(!isset($_GET['id']))
	{
		echo "<script>alert('Supplier ID not existing. Page automatically redirect');window.location='products.php'</script>";
	}else{

		$querySupplier = "SELECT * FROM tbl_supplier WHERE supplier_id='".$_GET['id']."'";
		$resultSupplier = mysqli_query($conn,$querySupplier);
	}
?>

<div id="supplier-add">
	<div class="container">
		<div id="center_column" class="center_column col-xs-10 col-sm-10">

			<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
				<?php while($supplier = mysqli_fetch_array($resultSupplier))
						{

				?>
				<div class="product_creation">
					<h3 class="page-subheading">Update Supplier Information</h3>
					<p class="text-danger"><?php echo $error;?></p>
					<div class="required form-group">
						<label for="username">Supplier Name <sup>*</sup></label>
						<input type="text" name="suppliername" class="form-control" required="true" value="<?php if(isset($supplier['suppliername'])){ echo $supplier['suppliername'];}?>">
					</div>
					<div class="required form-group">
						<label for="username">Contact Name<sup>*</sup></label>
						<input type="text" name="contactname" class="form-control" required="true" value="<?php if(isset($supplier['contactname'])){ echo $supplier['contactname'];}?>">
					</div>
					<div class="required form-group">
						<label for="username">Contact #<sup>*</sup></label>
						<input type="text" name="contactnumber" class="form-control" required="true" value="<?php if(isset($supplier['contactnumber'])){ echo $supplier['contactnumber'];}?>">
					</div>
					<div class="required form-group">
						<label for="username">Address<sup>*</sup></label>
						<input type="text" name="address" class="form-control" required="true" value="<?php if(isset($supplier['address'])){ echo $supplier['address'];}?>">
					</div>
					
				</div>
								
				<div class="submit clearfix">	
					<button type="submit" name="saveSupplier" id="saveSupplier" class="btn btn-default button button-medium">
						<span>Save Supplier<i class="icon-chevron-right right"></i></span>
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