<?php

include_once("admin-config.php");
include_once("admin-header.php");


	$error = '';
	if(isset($_POST['updateSize'])){

		

		$sizename = $_POST['sizename'];
		$description = $_POST['description'];
		$size_id = $_POST['size_id'];
		if (empty($sizename))
		{
	 		$error="Please fill out necessary fields";
	 	}
	 	
	 	if($error=='')
	 	{

			$query = "UPDATE tbl_size SET sizename='$sizename', description= '$description' WHERE size_id = $size_id";
			$result = mysqli_query($conn,$query);
			if ($result)
			{

				echo "<script>alert('Size successfully updated');</script>";
			}
			else
			{
				$error="Error Saving!";
			}
		}	

	}

	if(isset($_GET['size_id'])){

		$sizeUpdate = "SELECT * FROM tbl_size WHERE size_id=".$_GET['size_id']."";
		$resultSize = mysqli_query($conn,$sizeUpdate);
	}else{
		echo "<script>alert('Can not edit size');window.location='sizes.php'</script>";
	}
?>

<div id="size-add">
	<div id="page-wrapper">
		
		<div class="container-fluid">

	        <!-- Page Heading -->
	        <div class="row">
	            <div class="col-lg-12">
	                <h1 class="page-header">
	                    <small> Update Tire Size </small>
	                </h1>
	            </div>
	        </div>
			<div class="columns-container">
						<div>
							<?php 
								if(mysqli_num_rows($resultSize)>0)
								{
									while($data=mysqli_fetch_array($resultSize)){
							?>
									<form action="" method="POST" id="account-creation_form" class="std box" enctype="multipart/form-data">
										<input type="hidden" name="size_id" class="form-control" required="true" value="<?php echo $data['size_id'];?>">
										<div class="account_creation">
											<h3 class="page-subheading">Size Details</h3>
											<p class="text-danger"><?php echo $error;?></p>
											<div class="required form-group">
												<label for="username">Size Name <sup>*</sup></label>
												<input type="text" name="sizename" class="form-control" required="true" value="<?php echo $data['sizename'];?>">
											</div>
											<div class="required form-group">
												<label for="address">Size Description <sup>*</sup></label>
												 <textarea name="description" class="form-control" rows="5" id="description"><?php echo $data['description'];?></textarea>
											</div>	
										</div>
														
										<div class="submit clearfix">	
											<button type="submit" name="updateSize" id="updateSize" class="btn btn-default button button-medium">
												<span>Update Size<i class="icon-chevron-right right"></i></span>
											</button>
										</div>
									</form>
							<?php
									}
								}
							?>
						</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
include_once("admin-footer.php");
?>