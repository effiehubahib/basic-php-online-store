<?php 
	include_once("inc_header.php");

    $error="";
    $send=false;
    if(isset($_POST['btnContactUs'])){
        if(empty($_POST['name']))
        {
            $error .="Name is required";
        }
        if(empty($_POST['email']))
        {
            $error .="<br/>Email is required";
        }
        if(empty($_POST['message']))
        {
            $error .="<br/>Message is required";
        }

        if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['message']))
        {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $subject =$_POST['subject'];
            $phone =$_POST['phone'];
            $message = $_POST['message']."<br/>Phone: ".$phone;
            $to="test@gmail.com"; 
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            // More headers
            $headers .= 'From: <'.$email.'>' . "\r\n";
            $message = "Name: ".$name."\r\n \r\n".$message;
            mail($to,$subject,$message,$headers);

            $send = true;
        }
       
    }
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Contact Us</title>
</head>
<body>
<div class="container">
    <div class="row">
        <h1>Contact Us</h1>
        <div class="col-md-12">
            <div class="well well-sm">
                <?php if($send==true){?>
                    <div class="fluid-container">
                        <div class="alert alert-success"> Your message successfully sent </div>
                    </div>
                <?php   
                }else{
                ?>
                <form action="" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">
                                Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="email">
                                Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="phone">
                                Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter phone" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="subject">
                                Subject</label>
                            <select class="form-control" id="subject" name="subject" placeholder="Enter Subject" required="required" />
                                <option> Please Select</option>
                                <option value="Inquiry"> Inquiry</option>
                                <option value="Maintenance Request"> Maintenance Request</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">
                                Message</label>
                            <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required"
                                placeholder="Message"></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button type="submit" name="btnContactUs" class="btn btn-primary pull-right" id="btnContactUs">
                            Send Message</button>
                    </div>
                </div>
                </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

	

</body>
</html>