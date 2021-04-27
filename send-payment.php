<?php
include_once("inc_header.php");
include_once("config.php");

require_once('thumb_config.php'); 
$phpThumbBase  = 'lib/phpThumb/phpThumb.php';

$action = isset($_GET['action']) ? $_GET['action'] : "";
if(!isset($_GET['id']))
{
    header("Location: customer-dashboard.php"); 
}

$cart_id = $_GET['id'];

$query = "SELECT * FROM tbl_cart WHERE cart_id='".$cart_id."' && user_id='".$_SESSION["user_id"]."'";

$result = mysqli_query($conn,$query);
if(mysqli_num_rows($result)==0)
  header("Location: customer-dashboard.php"); 

if(isset($_POST['submitReceipt'])){

    if(isset($_FILES["payment_image"]["size"]) && $_FILES["payment_image"]["size"] >0){
          $check = getimagesize($_FILES["payment_image"]["tmp_name"]);
          $mime_list = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/x-windows-bmp');
            if(!in_array($check["mime"],$mime_list)){
              $error .= "<br/>File is not an image.";
            }
            if ($_FILES["payment_image"]["size"] > 5000000) {
              $error .= "<br/> Sorry, your file is too large.";
            }
            /*directory where to upload product images*/
            
            $upload_dir = "uploads/payments/";
            $upload_filename = time().".".basename($check["mime"]);
            $upload_file = $upload_dir .$upload_filename;
            $comment = addslashes($_POST['comment']);

            if (move_uploaded_file($_FILES["payment_image"]["tmp_name"], $upload_file)) {
                $query = "INSERT INTO tbl_payment(user_id, cart_id,payment_image,comment) VALUES('".$_SESSION["user_id"]."','$cart_id','$upload_filename','$comment')";
                $resultUpload = mysqli_query($conn,$query);

                $queryStatus = "UPDATE tbl_cart SET delivery_status = '1' WHERE cart_id = '$cart_id'";
                $result2 = mysqli_query($conn,$queryStatus);

                echo "<script>alert('Order receipt has been uploaded');window.location='send-payment.php?id=".$_GET['id']."'</script>";
                

            } else {
               $error .= "<br/>Sorry, there was an error uploading your file.";
            }
    }
}
$query = "SELECT * FROM tbl_payment WHERE cart_id='".$cart_id."'";
$result = mysqli_query($conn,$query);

?>

<div id="account">
    
    <div class="columns-container">
      <div id="columns" class="container">
           <h3>My Order</h3>
    <?php
      $queryProducts = "SELECT tp.product_name as product_name, cp.price as price, cp.quantity as quantity FROM tbl_cartproduct cp LEFT JOIN tbl_product tp ON cp.product_id = tp.product_id WHERE cart_id='".$_GET['id']."'";
      $resultProducts = mysqli_query($conn,$queryProducts);
         echo "<table class='table table-hover table-responsive table-bordered'>";
            // our table heading
         echo "<tr>";
                echo "<th class='textAlignLeft'>Product Name</th>";
                echo "<th>Quantity</th>";
                echo "<th>Unit Price</th>";
                echo "<th>Total Price</th>";
            echo "</tr>";
            $total_price=0;
            while($prod=mysqli_fetch_array($resultProducts)){
                echo "<tr>";
                    echo "<td>{$prod['product_name']}</td>";
                    echo "<td>{$prod['quantity']} </td>";
                    echo "<td>".number_format($prod['price'],2)."</td>";
                     echo "<td>".number_format($prod['quantity'] * $prod['price'],2)."</td>";
                echo "</tr>";
     
                $total_price+= ($prod['quantity'] * $prod['price']);
            }
     
            echo "<tr>";
                    echo "<td class='total' ><b>Overall Total</b></td>";
                    echo "<td></td>";
                    echo "<td></td>";
                    echo "<td class='total'>PHP ".number_format($total_price,2)."</td>";
                echo "</tr>";
     
        echo "</table>";

            if(mysqli_num_rows($result)>0)
            {   
                echo"<ul id='receipts' class=''>";
                while($row=mysqli_fetch_array($result))
                {
                      if($row['payment_image']!=null) {
                            $GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/arome.com/uploads/payments/".$row['payment_image']."&w=250";
                            
                          }
                          echo '<li><a href="#"><img border="0" src="'.htmlentities(phpThumbURL($GETstring, $phpThumbBase), ENT_QUOTES).'" alt="" ></a>
                          <p>Comment: '.$row['comment'].'</p>
                          </li>';
                }
                echo"</ul>";
            }else{
                echo"<h3>No payment receipt uploaded!</h3>"; 
            }
            ?>
           <div class="col-xs-12 col-sm-8 col-md-4">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                  <h3 class="panel-title">Upload Payment Receipt</h3>
                      <form action="" method="post" id="payment_receipt_form" class="std box" enctype="multipart/form-data">
                        <div class="form-group">
                          <label for="receipt_image">Upload Payment Receipt </label>
                          <input type="file" name="payment_image" id="payment_image" required="true">
                          
                        </div>
                        <div class="form-group">
                          <label for="comment">Comment:</label>
                          <textarea class="form-control" name="comment" rows="3" cols="32"></textarea>
                        </div>
                        <button type="submit" name="submitReceipt" class="btn btn-primary">Submit</button>
                      </form>
                </div>
            </div>
          </div>
      </div>  
  </div>

</div>

<?php
include_once("inc_footer.php");
?>
</body>
</html>