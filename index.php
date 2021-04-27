<?php
include_once("inc_header.php");

$queryProduct = "SELECT * FROM tbl_product LEFT JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id WHERE product_status = 1 AND quantity!=0";

$resultProducts = mysqli_query($conn,$queryProduct);
require_once('thumb_config.php');

$queryBrand = "SELECT * FROM tbl_brand WHERE brand_status = 1 ORDER BY id_brand DESC";
$resultBrand = mysqli_query($conn,$queryBrand);

$phpThumbBase  = 'lib/phpThumb/phpThumb.php';
?>
<div id="page">
	<div class="columns-container">
			<div id="columns" class="container">
				<div class="row">
					
					<div id="center_column" class="center_column col-xs-12 col-sm-12">
						
						<div>
							<ul id="blocknewproducts" class="product_list grid row blocknewproducts tab-pane active">
							<?php
							if(mysqli_num_rows($resultProducts)>0)
							{	
							while($product=mysqli_fetch_array($resultProducts))
							{
								?>
								<li class="col-xs-12 col-sm-4 col-md-3">
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
												<span itemprop="price" class="price product-price">PHP <?php echo number_format($product['price'],2);?></span>
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

	</div>
<?php
include_once("inc_footer.php");
?>
</body>
</html>