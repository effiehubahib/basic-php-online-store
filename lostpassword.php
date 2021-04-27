<?php
include_once("inc_header.php");
include_once("config.php");
$error = '';
$info = "";
$sendpassword =false;
    if(isset($_POST['SubmitPassword'])){
        $email = $_POST['email'];
        $query = "SELECT * FROM tbl_user WHERE email = '$email'";
        $result = mysqli_query($conn, $query) or die("Unable to query");

        if(mysqli_num_rows($result)>0){
           $row = mysqli_fetch_array($result);

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            $length = 10;

            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $query = "UPDATE tbl_user SET password='".md5($randomString)."' WHERE email = '$email'";
            
            $result = mysqli_query($conn, $query) or die("Unable to query");
            $to = $row['email'];
            $subject = "Your Password Recovery";
            $txt = "Hi ".$row['firstname']." ".$row['lastname'].",</br> Your password is:".$randomString;
            $headers = "From: admin@budgetelsystem.com" . "\r\n";

            mail($to,$subject,$txt,$headers);
            $info =' Check your email for your password';
            $sendpassword = true;
        }
        else{
            echo "<script>alert('No email found in database');</script>";
        }
    }
?>

<div id="lostpassword">
<div class="columns-container">
    <div>
        <div class="container">
            <div class="row">
                <div id="center_column" class="center_column col-xs-12 col-sm-12">
                    <div id="noSlide" style="display: block;"><h1 class="page-heading">Lost Password</h1>
                        <div class="col-xs-12 col-sm-6">
                            <?php if($sendpassword==true){
                                echo"<div class='alert alert-success'> New password sent to your email</div>";
                            }else{
                            ?>
                            <form action="" method="POST" id="login_form" class="box" enctype="multipart/form-data">
                                <h3 class="page-subheading">Enter email address</h3>
                                <p class="text-danger"><?php echo $error;?></p>
                                <?php if(isset($_GET['stat']) && $_GET['stat']==0){
                                        echo "<p class='text-danger'>Account is disabled. Contact Management</p>";
                                    }
                                ?>
                                <div class="form_content clearfix">
                                  
                                    <div class="form-group">
                                        <label for="passwd">Email</label>
                                        <input class="is_required validate  form-control" type="email" required="true" id="email" name="email" value="">
                                    </div>
                                    <p class="submit">
                                    
                                    <button type="submit" id="SubmitPassword" name="SubmitPassword" class="button btn btn-default button-medium"> 
                                            <span>
                                                Send to email
                                            </span>
                                        </button>
                                    </p>
                                </div>
                            </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>