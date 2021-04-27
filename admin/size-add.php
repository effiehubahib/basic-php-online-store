<?php

include_once("admin-config.php");
include_once("admin-header.php");


	$error = '';
	if(isset($_POST['addSize'])){

		

		$sizename = $_POST['sizename'];
		$description = $_POST['description'];
		if (empty($sizename))
		{
	 		$error="Please fill out necessary fields";
	 	}
	 	
	 	if($error=='')
	 	{

			$query = "INSERT INTO tbl_size( sizename, description) 
					VALUES('$sizename','$description')";
			$result = mysqli_query($conn,$query);
			if ($result)
			{

				echo "<script>alert('Size successfully created');window.location='sizes.php'</script>";
			}
			else
			{
				$error="Error Saving!";
			}
		}	

	}
?>

<div id="size-add">
	<div id="page-wrapper">
		
		<div class="container-fluid">

	        <!-- Page Heading -->
	        <div class="row">
	            <div class="col-lg-12">
	                <h1 class="page-header">
	                    <small> Add New Tire Size </small>
	                </h1>
	            </div>
	        </div>
			<div class="columns-container">
						<div>
							<form action="" method="POST" id="account-creation_form" class="std box" enctype="multipart/form-data">
			
								<div class="account_creation">
									<h3 class="page-subheading">Size Details</h3>
									<p class="text-danger"><?php echo $error;?></p>
									<div class="required form-group">
										<label for="username">Size Name <sup>*</sup></label>
										<input type="text" name="sizename" class="form-control" required="true" value="<?php if(isset($_POST['sizename'])){ echo $_POST['sizename'];}?>">
									</div>
									<div class="required form-group">
										<label for="address">Size Description <sup>*</sup></label>
										 <textarea name="description" class="form-control" rows="5" id="description"><?php if(isset($_POST['description'])){ echo $_POST['description'];}?></textarea>
									</div>	
								</div>
												
								<div class="submit clearfix">	
									<button type="submit" name="addSize" id="addSize" class="btn btn-default button button-medium">
										<span>Add Size<i class="icon-chevron-right right"></i></span>
									</button>
								</div>
							</form>
						</div>
			</div>
		</div>
	</div>
</div>