<?php

include_once("admin-config.php");
include_once("admin-header.php");


	$error = '';
	if(isset($_POST['addSupplier'])){

		

		$suppliername = $_POST['suppliername'];
		$contactnumber = $_POST['contactnumber'];
		$contactname = $_POST['contactname'];
		$address = $_POST['address'];

		if (empty($suppliername) )
		{
	 		$error="Please fill out necessary fields";
	 	
	 	}

	 	if($error=='')
	 	{

			$queryAddSupplier = "INSERT INTO tbl_supplier(suppliername,contactname,contactnumber,address) 
					VALUES('$suppliername','$contactname','$contactnumber','$address')";
			$result = mysqli_query($conn,$queryAddSupplier);
			if ($result)
			{
				echo "<script>alert('Supplier successfully created');window.location='suppliers.php'</script>";
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
                    <small> Add New Supplier </small>
                </h1>
            </div>
        </div>
		<div class="columns-container">
					<div>
						<form action="" method="post" id="account-creation_form" class="std box" enctype="multipart/form-data">
		
							<div class="account_creation">
								<p class="text-danger"><?php echo $error;?></p>
								<div class="required form-group">
									<label for="category_name">Supplier Name <sup>*</sup></label>
									<input type="text" name="suppliername" class="form-control" required="true" value="<?php if(isset($_POST['suppliername'])){ echo $_POST['suppliername'];}?>">
								</div>
								<div class="required form-group">
									<label for="category_name">Supplier Address <sup>*</sup></label>
									<input type="text" name="address" class="form-control" required="true" value="<?php if(isset($_POST['address'])){ echo $_POST['address'];}?>">
								</div>
								<div class="required form-group">
									<label for="category_name">Contact Number <sup>*</sup></label>
									<input type="text" name="contactnumber" class="form-control" required="true" value="<?php if(isset($_POST['contactnumber'])){ echo $_POST['contactnumber'];}?>">
								</div>
								<div class="required form-group">
									<label for="category_name">Contact Name<sup>*</sup></label>
									<input type="text" name="contactname" class="form-control" required="true" value="<?php if(isset($_POST['contactname'])){ echo $_POST['contactname'];}?>">
								</div>
							</div>
											
							<div class="submit clearfix">	
								<button type="submit" name="addSupplier" id="addSupplier" class="btn btn-default button button-medium">
									<span>Add New Supplier<i class="icon-chevron-right right"></i></span>
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