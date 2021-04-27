<?php
include_once("inc_header.php");
include_once("config.php");
if(isset($_SESSION["user_type"]) && ($_SESSION["user_type"]=="Staff"|| $_SESSION["user_type"]=="Admin")){
		header("Location: admin/index.php"); 
}elseif(isset($_SESSION["user_type"]) && $_SESSION["user_type"]=="Customer" ){
		header("Location: customer-dashboard.php"); 
}else{

}
	$error = '';
	if(isset($_POST['SubmitLogin'])){
		
		$password = $_POST['password'];
		$username = $_POST['username'];

		if (empty($username) || empty($password)) 
	 	{
	 		$error="Please fill out all the fields";
	 	} 

	 	if($error=='')
	 	{

			$query = "SELECT * FROM tbl_user WHERE username='".$username."' && password='".md5($password)."'";
			$result = mysqli_query($conn,$query);
			if($result->num_rows>0)
			{	
				while($credential=mysqli_fetch_array($result))
				{

					$_SESSION["user_name"] = $credential['firstname']." ".$credential['lastname'];
					$_SESSION["user_id"] = $credential['user_id'];
					$_SESSION["user_type"] = $credential['user_type'];
					$_SESSION["user_status"] = $credential['status'];
					if(($credential['user_type']=="Staff" || $credential['user_type']=="Admin") && $_SESSION["user_status"]==1)
					 	header("Location: admin/index.php"); 
					 elseif($_SESSION["user_status"]==0){
					 	session_destroy();
						header("Location: login.php?stat=0"); 
					 }
					 else{
					 	header("Location: customer-dashboard.php"); 
					 }
				}
			}
			else
			{
				$error="Credential not found in database!";
			}
		}	

	}

?>

<div id="login">
<div class="columns-container">
	<div>
		<div class="container">
			<div class="row">
				<div id="center_column" class="center_column col-xs-12 col-sm-12">
						<div class="col-xs-12 col-sm-6 col-md-offset-3">
							<form action="" method="POST" id="login_form" class="box" enctype="multipart/form-data">
								<h3 class="page-subheading">Member Login</h3>
								<p class="text-danger"><?php echo $error;?></p>
								<?php if(isset($_GET['stat']) && $_GET['stat']==0){
										echo "<p class='text-danger'>Account is disabled. Contact Management</p>";
								}
								?>
								<div class="form_content clearfix">
									<div class="form-group">
										<label for="username">Username</label>
										<input class="is_required validate account_input form-control" data-validate="isEmail" type="text" id="username" name="username" value="">
									</div>
									<div class="form-group">
										<label for="passwd">Password</label>
										<input class="is_required validate account_input form-control" type="password" id="password" name="password" value="">
									</div>
									<p class="lost_password form-group"><a href="lostpassword.php" title="Recover your forgotten password" rel="nofollow">Forgot your password?</a></p>
									<p class="submit">
									
									<button type="submit" id="SubmitLogin" name="SubmitLogin" > 
											<span>Sign in</span>
										</button>
									</p>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>