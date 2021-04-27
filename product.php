<?php
include_once("inc_header.php");

if(!isset($_GET['id']))
	{
		echo "<script>alert('Product ID not existing. Page automatically redirect');window.location='index.php'</script>";
	}else{

		$query = "SELECT * FROM tbl_product p LEFT JOIN tbl_brand b ON p.brand_id = b.brand_id WHERE product_id = ".$_GET['id']." AND product_status = 1 ";

		$result = mysqli_query($conn,$query);
}

require_once('thumb_config.php');


$phpThumbBase  = 'lib/phpThumb/phpThumb.php';
?>
	<div id="page">
		
		<div class="columns-container">
			<?php
				if($result->num_rows>0)
				{	
					while($product=mysqli_fetch_array($result))
					{ 

						?>
			
			<div id="columns" class="container">
			
				<div class="row">
					<div id="center_column" class="center_column col-xs-12 col-sm-12">
						
						<div id="pd_column" class=" col-xs-12 col-sm-12 col-md-7">
							<div class=" col-xs-12 col-sm-12 col-md-12 product-block">
								<?php if(isset($_GET['action']) && $_GET['action']=="exists")
										 echo "<div class='alert alert-info alertDisplay' role='alert'><strong>Alert!</strong> Product already exists in your cart. <a href='cart.php'>Go to Cart </a></div>";
									  if(isset($_GET['action']) && $_GET['action']=="added")
										 echo "<div class='alert alert-success alertDisplay' role='alert'><strong>Well done!</strong> Successfully added in your cart</div>";
								?>
									<h1><?php echo ucfirst($product['product_name']);?> 
										<span class="pull-right" style="color:#313e9c;"> Price: <?php echo number_format($product['price'],2);?></span>
									</h1>
									
									<?php if($product['quantity']>0){?>
									<h2><?php echo $product['quantity'];?> items in stock</h2>	
									<div id="productBox" class=" col-xs-12 col-sm-12 col-md-12">

									<form method="POST" action="product-add-to-cart.php" enctype="multipart/form-data">

										<p id="quantity_wanted_p">
												<label for="quantity_wanted">Quantity</label>
												<input type="hidden" id="product_stock" name="product_stock" value="<?php echo $product['quantity'];?>" class="form-control">
											    <input type="text" min="1" name="qty"onkeyup="validateNumOnly(this,'b')" id="quantity_wanted" class="text" value="1" style="border: 1px solid rgb(189, 194, 201);">
											    <input type="hidden" name="product_id" value="<?php echo $product['product_id'];?>">
											    <input type="hidden" name="price" value="<?php echo $product['price'];?>">
											    <a href="#" data-field-qty="qty" data-maxval="<?php echo $product['quantity'];?>" class="btn btn-default button-minus product_quantity_down">
													<span><i class="icon-minus"></i></span>
												</a>
												<a href="#" data-field-qty="qty" data-maxval="<?php echo $product['quantity'];?>" 	 class="btn btn-default button-plus product_quantity_up">
													<span><i class="icon-plus"></i></span>
												</a>
										</p>
										<p id="validate"></p>
										<p id="add_to_cart" class="buttons_bottom_block no-print">
											<button type="submit" name="Submit" class="exclusive">
												<span>Add to cart</span>
											</button>
										</p>
									</form>
									</div>
									<?php 
									}else{
										echo"<div class='alert alert-danger'>Sorry! No stocks available</div>";
									}
									?>
									
							</div>
							
						</div>
						<div id="pt_column" class=" col-xs-12 col-sm-12 col-md-5">
							<div class="product-image">
								<?php echo "<a href='uploads/products/".$product['product_image']."' data-lightbox='image-1' 
									data-title='".$product['product_name']."'>";
									
										if($product['product_image']!=null) {
											$GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/arome.com/uploads/products/".$product['product_image']."&w=458";
											
										}else{
											$GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/sbq-furnitures/uploads/products/no-image.jpg&w=458";
										}
										echo '<img border="0" src="'.htmlentities(phpThumbURL($GETstring, $phpThumbBase), ENT_QUOTES).'" alt="" >';

								
								echo "</a>";
								?>
							</div>
							<div class="prod_desc"><?php echo $product['description'];?></div>
						</div>
					</div>
				</div>
			</div>
			<?php	
					}
				}else{
					echo"This product not available"; 
				}
				?>
		</div>

	</div>

<script src="lib/lightbox2/dist/js/lightbox-plus-jquery.min.js"></script>
<script>
$( "form" ).submit(function( event ) {
  var TheVar = parseInt($("#quantity_wanted").val(), 10);
  var stockProduct = parseInt($("#product_stock").val(), 10);
  if(TheVar && TheVar>stockProduct){
  		$( "p#validate" ).text( "Stock not enough!" ).show().fadeOut( 1000 );
  		event.preventDefault();
  		
  }else if ( TheVar && TheVar > 0) {
  		//$( "p#validate" ).text( "Validated..." ).show();
  		return;
  }else
  {

  		$( "p#validate" ).text( "Not valid!" ).show().fadeOut( 1000 );
  		event.preventDefault();
  }
  
  
});
</script>

<?php
include_once("inc_footer.php");
?>
</body>
</html>