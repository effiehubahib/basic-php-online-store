<?php
include_once("admin_config.php");
include_once("admin_header.php");

if(!isset($_GET['id']))
{
    header("Location: staff.php"); 
}
if(isset($_SESSION["user_type"]) && $_SESSION["user_type"]==3){
		
	$query = "SELECT * FROM tbl_user WHERE id='".$_GET['id']."'";
	$result = mysqli_query($conn,$query);

}elseif(isset($_SESSION["user_type"]) && $_SESSION["user_type"]==2){
	header("Location: index.php"); 
}else{
	header("Location: ../login.php"); 
}


if(mysqli_num_rows($result)>0){
	while($info=mysqli_fetch_array($result)){
		$firstname = $info['firstname'];
		$lastname = $info['lastname'];
		$address = $info['address'];
		$username = $info['username'];
		$email = $info['email'];
		$contact_no = $info['contact_no'];
		$birthdate = $info['birthdate'];
		$gender = $info['gender'];
	}
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
		$password = $_POST['password'];
		$confirm_password = $_POST['confirm_password'];
		if (empty($firstname) || empty($lastname)|| empty($address) ||empty($username) ||  empty($email) || empty($contact_no)||
	 		empty( $birthdate) || empty($gender) ) 
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
		

	 	if($error=='' && $error2=='')
	 	{

	 		if(!empty($password) && !empty($confirm_password)){
				if ($password !=  $confirm_password)
			 	 {
			 	 	$error="Password Mismatch! ";
			 	} 
			 	if (strlen($password) <=6)
			 	{
			 	 	$error2="Password character must be greater than 6! ";
			 	}else{
			 		$encrypt = md5($password);
			 		$queryPassword = "UPDATE tbl_user SET
					password = '$encrypt' 
					WHERE id='".$_GET['id']."'";
					$resultPassword = mysqli_query($conn,$queryPassword);
			 	}
			}

			if($error=='' && $error2=='')
		 	{
				$query = "UPDATE tbl_user SET
					firstname = '$firstname',
					lastname = '$lastname',
					address = '$address',
					username = '$username',
					email = '$email',
					contact_no = '$contact_no',
					birthdate = '$birthdate',
					gender = '$gender' 
					WHERE id='".$_GET['id']."'";

				$result = mysqli_query($conn,$query);
				if ($result)
				{	
					echo "<script>alert('Staff Account Updated');window.location='staff.php'</script>";
				}
				else
				{
					$error="Error Saving!";
				}
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
						<h1 class="page-heading">Create an account</h1>

	<form action="" method="post" id="account-creation_form" class="std box">
		
		<div class="account_creation">
			<h3 class="page-subheading">Your personal information</h3>
			<p class="text-danger"><?php echo $error;?></p>
			<p class="text-danger"><?php echo $error2;?></p>
			<div class="required form-group">
				<label for="username">Username <sup>*</sup></label>
				<input type="text" name="username" class="form-control" required="true" value="<?php if(isset($username)){ echo $username;}?>">
			</div>
			<div class="required form-group">
				<label for="firstname">First name <sup>*</sup></label>
				<input type="text" name="firstname" class="form-control" required="true" value="<?php if(isset($firstname)){ echo $firstname;}?>">
			</div>
			<div class="required form-group">
				<label for="lastname">Last name <sup>*</sup></label>
				<input type="text" name="lastname" class="form-control" required="true" value="<?php if(isset($lastname)){ echo $lastname;}?>">
			</div>
			<div class="required form-group">
				<label for="address">Address <sup>*</sup></label>
				<input type="text" name="address" class="form-control" required="true" value="<?php if(isset($address)){ echo $address;}?>">
			</div>
			<div class="required form-group">
				<label for="email">Email <sup>*</sup></label>
				<input type="email" name="email" class="form-control" required="true" value="<?php if(isset($email)){ echo $email;}?>">
			</div>
			<div class="required form-group">
				<label for="contact_no">Contact No. <sup>*</sup></label>
				<input type="text" name="contact_no" class="form-control" required="true" value="<?php if(isset($contact_no)){ echo $contact_no;}?>">
			</div>
			<div class="required form-group">
				<label for="birthday">Birthdate <sup>*</sup></label>
				<input type="text" name="birthdate" class="form-control" required="true" value="<?php if(isset($birthdate)){ echo $birthdate;}?>">
			</div>
			<div class="required form-group gender">
				<label for="gender">Gender <sup>*</sup></label>
				<input type="radio" name="gender" value="Male" class="form-control" required="true" <?php if(isset($gender) && $gender=='Male'){ echo "checked='checked'";}?>>Male
				<input type="radio" name="gender" value="Female" class="form-control" required="true" <?php if(isset($gender) && $gender=='Female'){ echo "checked='checked'";}?>>Female
			</div>
			<div class="required password form-group">
				<label for="passwd">Password <sup>*</sup></label>
				<input type="password" name="password" class="form-control">
				<span class="form_info">(Five characters minimum)</span>
			</div>
			<div class="required password form-group">
				<label for="passwd">Confirm Password <sup>*</sup></label>
				<input type="password" name="confirm_password" class="form-control">
			</div>						
		</div>
						
		<div class="submit clearfix">	
			<button type="submit" name="submitRegister" id="submitRegister" class="btn btn-default button button-medium">
				<span>Register<i class="icon-chevron-right right"></i></span>
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