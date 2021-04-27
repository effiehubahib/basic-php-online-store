<?php

include_once("admin-config.php");
include_once("admin-header.php");


	$error = '';
	if(isset($_POST['addBrand'])){

		

		$brandname = $_POST['brandname'];
		$queryexistCat = "SELECT * FROM tbl_brand WHERE brandname ='".$brandname."'";
		$resultExist = mysqli_query($conn,$queryexistCat);

		if (empty($brandname) )
		{
	 		$error="Please fill out the field";
	 	}elseif(mysqli_num_rows($resultExist)>0){
	 		$error="Brand already exists!";
	 	}
	 	

	 	if($error=='')
	 	{

			$queryAddBrand = "INSERT INTO tbl_brand(brandname) 
					VALUES('$brandname')";
			$result = mysqli_query($conn,$queryAddBrand);
			if ($result)
			{
				echo "<script>alert('Brand successfully created');window.location='brands.php'</script>";
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
                    <small> Add New Brand </small>
                </h1>
            </div>
        </div>
		<div class="columns-container">
					<div>
						<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
		
							<div class="account_creation">
								<p class="text-danger"><?php echo $error;?></p>
								<div class="required form-group">
									<label for="category_name">Brand Name <sup>*</sup></label>
									<input type="text" name="brandname" class="form-control" required="true" value="<?php if(isset($_POST['brandname'])){ echo $_POST['brandname'];}?>">
								</div>	
								<div class="required form-group product-status">
									<label for="contact_no">Brand Status<sup>*</sup></label>
									<input type="radio" name="brand_status" value="1" class="form-control" required="true" <?php if(isset($_POST['brand_status']) && $_POST['brand_status']==1){ echo "selected='selected'";}?> > Available
									<input type="radio" name="brand_status" value="0" class="form-control" required="true" <?php if(isset($_POST['brand_status']) && $_POST['brand_status']==0){ echo "selected='selected'";}?> >Not Available
								</div>
							</div>
											
							<div class="submit clearfix">	
								<button type="submit" name="addBrand" id="addBrand" class="btn btn-default button button-medium">
									<span>Add New Brand<i class="icon-chevron-right right"></i></span>
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