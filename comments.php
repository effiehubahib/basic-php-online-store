<?php 
	include_once("inc_header.php");
    include_once("config.php");

    $error = '';
    if(isset($_POST['btnComment'])){
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            if (empty($fullname) ||  empty($email) ||  empty($message) )
            {
                $error="Please fill out all the fields";
            }
            
            if($error=='')
            {

                $query = "INSERT INTO tbl_comment( fullname, email, message,status) 
                        VALUES('$fullname','$email','$message',0)";
                $result = mysqli_query($conn,$query);
                if ($result)
                {

                    echo "<script>alert('Comment successfully submitted to the management');window.location='comments.php?stat=1'</script>";
                }
                else
                {
                    $error="Error Saving!";
                }
            }   

        }
    $queryComment = "SELECT * FROM tbl_comment WHERE status = 1 ORDER BY id_comment DESC";
    $resultComment = mysqli_query($conn,$queryComment);


 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Contact Us</title>
</head>
<body>
	<div class="jumbotron jumbotron-sm">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <h1 class="h1">Comments </h1>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php
                if($resultComment->num_rows>0)
                {   
                    while($comment=mysqli_fetch_array($resultComment))
                    {
                        
            ?>  <div>
                    <?php echo $comment['message']?>
                </div>
                <div class="commentUser">
                <h5><strong> <i class="fa fa-user" aria-hidden="true"></i> <?php echo $comment['fullname']?> , <?php echo date("F d Y H:i:s", strtotime($comment['date_created']));?></strong></h5>
                </div>  

            <?php
                    }
                }else{
                    echo "<h2>No comments yet</h2>";
                }
            ?>
        </div>
        <div class="col-md-4">
            <div class="well well-sm">
                <form method="POST" action="">
                <div class="row">
                    <div class="col-md-11 commentForm">

                        <h2>Send a Comment</h2>

                        <?php if(isset($_GET['stat'])){ ?>
                            <div class="form-group">
                                  <h3>Thank you for submitting your comment</h3>
                            </div>
                        <?php 
                            }
                            else
                            {
                         ?>   
                        <div class="form-group">
                            <label for="name">
                                Name</label>
                            <input type="text" name="fullname" class="form-control" id="fullname" placeholder="Enter name" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="email">
                                Email Address</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required="required" />
                        </div>
                    
                        <div class="form-group">
                            <label for="name">
                                Comment</label>
                            <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required"
                                placeholder="Message"></textarea>
                        </div>
                         <div class="col-md-12">
                            <button type="submit" name ="btnComment" class="btn btn-primary " id="btnComment">
                                Submit Comment</button>
                        </div>
                      <?php  
                        }
                        ?>
                    </div>
                   
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

	

</body>
</html>