<?php
include_once("inc_header.php");
include_once("config.php");

require_once('thumb_config.php'); 
$phpThumbBase  = 'lib/phpThumb/phpThumb.php';

$action = isset($_GET['action']) ? $_GET['action'] : "";
if(!isset($_GET['id']))
{
    header("Location: account.php"); 
}

$cart_id = $_GET['id'];
$query = "SELECT * FROM tbl_cart WHERE id_cart='".$cart_id."' && id_user='".$_SESSION["id"]."'";
$result = mysqli_query($conn,$query);
//check if current Cart owns by logged in user else redirect to account.php
if($result->num_rows<=0)
  header("Location: account.php"); 

if(isset($_POST['submitReceipt'])){

    if(isset($_FILES["receipt_image"]["size"]) && $_FILES["receipt_image"]["size"] >0){
          $check = getimagesize($_FILES["receipt_image"]["tmp_name"]);
          $mime_list = array('image/jpeg','image/pjpeg','image/png','image/gif','image/bmp','image/x-windows-bmp');
            if(!in_array($check["mime"],$mime_list)){
              $error .= "<br/>File is not an image.";
            }
            if ($_FILES["receipt_image"]["size"] > 5000000) {
              $error .= "<br/> Sorry, your file is too large.";
            }
            /*directory where to upload product images*/
            
            $target_dir = "uploads/receipts/";
            $target_filename = time().".".basename($check["mime"]);
            $target_file = $target_dir .$target_filename;
            $note = addslashes($_POST['note']);

            if (move_uploaded_file($_FILES["receipt_image"]["tmp_name"], $target_file)) {
                $query = "INSERT INTO tbl_upload_payment_images(user_id, cart_id,imagename,note) VALUES('".$_SESSION["id"]."','$cart_id','$target_filename','$note')";
                $resultUpload = mysqli_query($conn,$query);

                $queryStatus = "UPDATE tbl_cart SET delivery_status = '1' WHERE id_cart = '$cart_id'";
                $result2 = mysqli_query($conn,$queryStatus);

                echo "<script>alert('Order receipt has been uploaded');window.location='upload-receipt.php?id=".$_GET['id']."'</script>";
                

            } else {
               $error .= "<br/>Sorry, there was an error uploading your file.";
            }
    }
}
$query = "SELECT * FROM tbl_upload_payment_images WHERE cart_id='".$cart_id."'";
$result = mysqli_query($conn,$query);

?>

<div id="account">
    
    <div class="columns-container">
    <div id="columns" class="container">
        <nav class="navbar navbar-default">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="account.php">My Account</a>
            </div>

          </div><!-- /.container-fluid -->
        </nav>

    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Kindly Upload Payment Receipt</h3>
      </div>
      <div class="panel-body">
        <div class="col-xs-12 col-sm-8 col-md-4">
            <form action="" method="post" id="payment_receipt_form" class="std box" enctype="multipart/form-data">
              <div class="form-group">
                <label for="receipt_image">File Upload</label>
                <input type="file" name="receipt_image" id="receipt_image" required="true">
                
              </div>
              <div class="form-group">
                <label for="note">Note:</label>
                <textarea class="form-control" name="note" rows="3" cols="32"></textarea>
              </div>
              <button type="submit" name="submitReceipt" class="btn btn-primary">Submit</button>
            </form>
        </div>
      </div>
    </div>
    <h1 class="page-heading">Your Order</h1>
    <?php
      $queryProducts = "SELECT tp.product_name as product_name, cp.price as price, cp.quantity as quantity FROM tbl_cart_product cp LEFT JOIN tbl_product tp ON cp.id_product = tp.id_product WHERE id_cart='".$_GET['id']."'";
      $resultProducts = mysqli_query($conn,$queryProducts);
         echo "<h4>Products</h4>
                <table class='table table-hover table-responsive table-bordered'>";
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

            if($result->num_rows>0)
            {   
                echo"<ul id='receipts' class=''>";
                while($row=mysqli_fetch_array($result))
                {
                      if($row['imagename']!=null) {
                            $GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/sbq-furnitures/uploads/receipts/".$row['imagename']."&w=250";
                            
                          }
                          echo '<li><a href="#"><img border="0" src="'.htmlentities(phpThumbURL($GETstring, $phpThumbBase), ENT_QUOTES).'" alt="" ></a>
                          <p>'.$row['note'].'</p>
                          </li>';
                }
                echo"</ul>";
            }else{
                echo"<h2>No payment receipt yet!</h2>"; 
            }
            ?>
    </div>
    </div>

</div>

<?php
include_once("inc_footer.php");
?>
</body>
</html>