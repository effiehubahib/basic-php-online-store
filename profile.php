<?php
include_once("inc_header.php");
include_once("config.php");
include_once('account_session.php'); 

    $error = '';
    $error2 = '';

    if(isset($_POST['updateProfile'])){

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $contact_no = $_POST['contact_no'];
        $birthdate = $_POST['birthdate'];
        $gender = $_POST['gender'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $user_type = 1;
        $status = 1;
        if (empty($firstname) || empty($lastname)|| empty($address) || empty($email) || empty($contact_no)||
            empty( $birthdate) || empty($gender)) 
        {
            $error="Please fill out all the fields";
        } 

       
        if (!empty($password) && ($password !=  $confirm_password))
         {
            $error="Password Mismatch! ";
        } 
        if (!empty($password) && strlen($password) <=6)
        {
            $error2="Password character must be greater than 6! ";
        } 

        if($error=='' && $error2=='')
        {

            $query = "UPDATE tbl_user SET firstname = '$firstname',lastname ='$lastname',address ='$address',email = '$email',contact_no = '$contact_no', birthdate ='$birthdate',gender = '$gender' WHERE user_id='".$_SESSION['id']."'";
            $result = mysqli_query($conn,$query);

            if ($result)
            {   
                if (!empty($password) && ($password ==  $confirm_password))
                {
                    $query = "UPDATE tbl_user SET password = '".md5($password)."' WHERE user_id='".$_SESSION['id']."'";
                    $result = mysqli_query($conn,$query);
                }
                
                echo "<script>alert('Account Updated');window.location='login.php'</script>";
            }
            else
            {
                $error="Error Saving!";
            }
        }   

    }
    $query = "SELECT * FROM tbl_user WHERE user_id= '".$_SESSION['user_id']."'";
    
    $result = mysqli_query($conn,$query);
    if($result->num_rows>0)
    {   
        while($profile=mysqli_fetch_array($result))
        {

?>
<div id="register">
<div class="columns-container">
    <div>
        <div class="container">
          <div class="row">
          <div id="center_column" class="center_column col-xs-12 col-sm-12">
            <div id="noSlide" style="display: block;">
                          

            <form action="" method="post" id="account-creation_form" class="std box">
                
                <div class="account_creation">
                    <h3 class="page-subheading">Your personal information </h3>
                    <p class="text-danger"><?php echo $error;?></p>
                    <p class="text-danger"><?php echo $error2;?></p>
                   
                    <div class="required form-group">
                        <label for="firstname">First name <sup>*</sup></label>
                        <input type="text" name="firstname" class="form-control" required="true" value="<?php echo $profile['firstname'];?>">
                    </div>
                    <div class="required form-group">
                        <label for="lastname">Last name <sup>*</sup></label>
                        <input type="text" name="lastname" class="form-control" required="true" value="<?php echo $profile['lastname'];?>">
                    </div>
                    <div class="required form-group">
                        <label for="address">Address <sup>*</sup></label>
                        <input type="text" name="address" class="form-control" required="true" value="<?php echo $profile['address'];?>">
                    </div>
                    <div class="required form-group">
                        <label for="email">Email <sup>*</sup></label>
                        <input type="email" name="email" class="form-control" required="true" value="<?php echo $profile['email'];?>">
                    </div>
                    <div class="required form-group">
                        <label for="contact_no">Contact No. <sup>*</sup></label>
                        <input type="text" name="contact_no" class="form-control" required="true" value="<?php echo $profile['contact_no'];?>">
                    </div>
                    <div class="required form-group">
                        <label for="birthday">Birthdate <sup>*</sup></label>
                        <input type="date" name="birthdate" class="form-control" required="true" value="<?php echo $profile['birthdate'];?>">
                    </div>
                    <div class="required form-group gender">
                        <label for="gender">Gender <sup>*</sup></label>
                        <input type="radio" name="gender" value="Male" class="form-control" required="true" <?php if(isset($profile['gender']) && $profile['gender']=='Male'){ echo "checked='checked'";}?>>Male
                        <input type="radio" name="gender" value="Female" class="form-control" required="true" <?php if(isset($profile['gender']) && $profile['gender']=='Female'){ echo "checked='checked'";}?>>Female
                    </div>
                    <div class="required password form-group">
                        <label for="passwd">Password (Leave blank if don't change)</label>
                        <input type="password" name="password" class="form-control" >
                        <span class="form_info">(Five characters minimum)</span>
                        <label for="passwd">Confirm Password (Leave blank if don't change)<sup>*</sup></label>
                         <input type="password" name="birthdate" class="form-control">
                    </div>                      
                </div>
                                
                <div class="submit clearfix">   
                    <button type="submit" name="updateProfile" id="updateProfile">
                        <span>Register</span>
                    </button>
                </div>
            </form>

            </div><!-- #center_column -->
          </div>
          </div>
        </div>
    </div>

</div>
<?php
    }

}
?>