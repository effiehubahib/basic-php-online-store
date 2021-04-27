<?php
include_once("inc_header.php");
include_once("config.php");

if(!isset($_GET['id']))
	{
		echo "<script>alert('Brand ID not existing. Page automatically redirect');window.location='index.php'</script>";
	}else{

		$query = "SELECT * FROM tbl_product WHERE brand_id = ".$_GET['id']." AND product_status = 1 ORDER BY product_id";
		$result = mysqli_query($conn,$query);
}

require_once('thumb_config.php');

$phpThumbBase  = 'lib/phpThumb/phpThumb.php';
?>
	<div id="page">
		
		<div class="columns-container">
			<div id="columns" class="container">
				<div class="row">
					
					<div id="center_column" class="center_column col-xs-12 col-sm-12">
					
						<ul id="blocknewproducts" class="product_list grid row blocknewproducts tab-pane active">
						<?php
						if(mysqli_num_rows($result)>0)
						{	
							while($product=mysqli_fetch_array($result))
							{
								?>
								<li class="col-xs-12 col-sm-4 col-md-3" style="border:1px solid #ccc;">
									<div>
										<a href="product.php?id=<?php echo $product['product_id'];?>"><h4><?php echo $product['product_name'];?></h4></a>
										<div class="product-image-container">
											<a class="product_img_link" href="product.php?id=<?php echo $product['product_id'];?>" >
												<?php 
													if($product['product_image']!=null) {
														$GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/arome.com/uploads/products/".$product['product_image']."&w=250";
														
													}else{
														$GETstring = "src=".$_SERVER['DOCUMENT_ROOT']."/arome.com/uploads/products/no-image.jpg&w=250";
													}
													echo '<img border="0" src="'.htmlentities(phpThumbURL($GETstring, $phpThumbBase), ENT_QUOTES).'" alt="" >';

												?>
											</a>
										</div>
										<div class="product-description">
											<div class="content_price" itemprop="offers" itemscope="" itemtype="">
												<span itemprop="price" class="price product-price">PHP <?php echo $product['price'];?></span>
											</div>
											<br/>
											<p><?php echo $product['description'];?></p>
										</div>
									</div>
								</li>
								<?php
							}
						}else{
							echo"No product available"; 
						}
						?>
								
						</ul>
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