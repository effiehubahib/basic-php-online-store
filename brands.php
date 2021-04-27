<?php
include_once("inc_header.php");
include_once("config.php");



$query = "SELECT * FROM tbl_brand WHERE brand_status = 1";
$result = mysqli_query($conn,$query);


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
							while($brand=mysqli_fetch_array($result))
							{
								?>
								<li class="col-xs-12 col-sm-4 col-md-3" style="border:1px solid #ccc;">
									<div>
										
										<div class="product-description">
											<a href="brand-products.php?id=<?php echo $brand['brand_id'];?>"><h4><?php echo $brand['brandname'];?></h4></a>
										</div>
									</div>
								</li>
								<?php
							}
						}else{
							echo"No brand is active yet"; 
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