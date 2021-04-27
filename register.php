<?php
include_once("inc_header.php");
include_once("config.php");
if(isset($_SESSION["user_type"]) && $_SESSION["user_type"]==3 && isset($_SESSION["id"])){
		header("Location: admin/index.php"); 
}elseif(isset($_SESSION["user_type"]) && $_SESSION["user_type"]==1 && isset($_SESSION["id"])){
		header("Location: account.php"); 
}else{

}
?>

<?php
	$error = '';
 	$error2 = '';

	if(isset($_POST['submitRegister'])){

		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$address = $_POST['address'];
		$username = $_POST['username'];
		$email = $_POST['email'];
		$contact_no = $_POST['contact_no'];
		$birthdate = $_POST['birthdate'];
		$gender = $_POST['gender'];
		$password = md5($_POST['password']);
		$confirm_password = md5($_POST['confirm_password']);
		$user_type = "Customer";
		$status = 1;
		if (empty($firstname) || empty($lastname)|| empty($address) ||empty($username) ||  empty($email) || empty($contact_no)||
	 		empty( $birthdate) || empty($gender) || empty($password) ) 
	 	{
	 		$error="Please fill out all the fields";
	 	} 

	 	if (isset($_POST['$username']) && preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $_POST["$username"])) 
	 	{
		 	$error="Special characters are not allowed as a username! ";
		}
		if (isset($username) && preg_match("/\\s/",  $username)) 
		{
	   		$error2 = "Username should not contain spaces! ";
		}
		if ($password !=  $confirm_password)
	 	 {
	 	 	$error="Password Mismatch! ";
	 	} 
	 	if (strlen($password) <=6)
	 	{
	 	 	$error2="Password character must be greater than 6! ";
	 	} 

	 	if($error=='' && $error2=='')
	 	{
			$query = "INSERT INTO tbl_user(firstname,lastname,address,email,contact_no,username,password,birthdate,gender,user_type,status) 
					VALUES('$firstname','$lastname','$address','$email','$contact_no','$username','$password','$birthdate','$gender','$user_type',$status)";

			$result = mysqli_query($conn,$query);
			if ($result)
			{	
				echo "<script>alert('Account Registered');window.location='login.php'</script>";
			}
			else
			{
				$error="Error Saving!";
			}
		}	

	}

?>
<div id="register">
<div class="columns-container">
			<div>
				<div class="container">
					<div class="row">
						<div id="center_column" class="center_column col-xs-12 col-sm-12"><div id="noSlide" style="display: block;">

	<form action="" method="post" id="account-creation_form" class="std box">
		
		<div class="account_creation">
			<h3 class="page-subheading">Complete the fields below for registration</h3>
			<p class="text-danger"><?php echo $error;?></p>
			<p class="text-danger"><?php echo $error2;?></p>
			<div class="required form-group">
				<label for="username">Username <sup>*</sup></label>
				<input type="text" name="username" class="form-control" required="true" value="<?php if(isset($_POST['username'])){ echo $_POST['username'];}?>">
			</div>
			<div class="required form-group">
				<label for="firstname">First name <sup>*</sup></label>
				<input type="text" name="firstname" class="form-control" required="true" value="<?php if(isset($_POST['firstname'])){ echo $_POST['firstname'];}?>">
			</div>
			<div class="required form-group">
				<label for="lastname">Last name <sup>*</sup></label>
				<input type="text" name="lastname" class="form-control" required="true" value="<?php if(isset($_POST['lastname'])){ echo $_POST['lastname'];}?>">
			</div>
			<div class="required form-group">
				<label for="address">Address <sup>*</sup></label>
				<input type="text" name="address" class="form-control" required="true" value="<?php if(isset($_POST['address'])){ echo $_POST['address'];}?>">
			</div>
			<div class="required form-group">
				<label for="birthday">Birthdate <sup>*</sup></label>
				<input type="text" name="birthdate" class="form-control" required="true" value="<?php if(isset($_POST['birthdate'])){ echo $_POST['birthdate'];}?>">
			</div>
			<div class="required form-group gender">
				<label for="gender">Gender <sup>*</sup></label>
				<input type="radio" name="gender" value="Male" class="form-control" required="true" <?php if(isset($_POST['gender']) && $_POST['gender']=='Male'){ echo "selected='selected'";}?>>Male
				<input type="radio" name="gender" value="Female" class="form-control" required="true" <?php if(isset($_POST['gender']) && $_POST['gender']=='Female'){ echo "selected='selected'";}?>>Female
			</div>
			<div class="required password form-group">
				<label for="passwd">Password <sup>*</sup></label>
				<input type="password" name="password" class="form-control" required="true">
			</div>
			<div class="required password form-group">
				<label for="passwd">Confirm Password <sup>*</sup></label>
				<input type="password" name="confirm_password" class="form-control" required="true">
			</div>
			<div class="required form-group">
				<label for="email">Email <sup>*</sup></label>
				<input type="email" name="email" class="form-control" required="true" value="<?php if(isset($_POST['email'])){ echo $_POST['email'];}?>">
			</div>
			<div class="required form-group">
				<label for="contact_no">Contact No. <sup>*</sup></label>
				<input type="text" name="contact_no" class="form-control" required="true" value="<?php if(isset($_POST['contact_no'])){ echo $_POST['contact_no'];}?>">
			</div>
									
		</div>
						
		<div class="submit clearfix">	
			<button type="submit" name="submitRegister" id="submitRegister">
				<span>Register</span>
			</button>
		</div>
	</form>
</div>
</div><!-- #center_column -->
</div>

		</div>
	</div>
</div>

</div>