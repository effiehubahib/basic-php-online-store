<?php
require('../lib/fpdf181/fpdf.php');
include_once("admin_config.php");


if(!isset($_GET['day'])){
	$querySoldToday = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE DATE(tbl_cart.dateadded) = DATE(NOW()) &&
		      						tbl_cart.delivery_status>2";
}else{

	$querySoldToday = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE DATE(tbl_cart.dateadded) = DATE('".$_GET['day']."') &&
		      						tbl_cart.delivery_status>2";
}
$resultSoldToday = mysqli_query($conn,$querySoldToday);


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(70);
$pdf->Cell(0,20,'SBQ Furniture');
$pdf->Ln(22);
$pdf->Cell(40);
$pdf->SetFont('Arial','',12);
$pdf->Cell(1,0,"Today's Sold Product: ".date("F d, Y") );
$pdf->Ln(12);

    
if(mysqli_num_rows($resultSoldToday)>0){
	$header = array('Order #','Product Name', 'Quantity', 'Unit Price', 'Total');
 	foreach($header as $col)
 		if($col!='Quantity')
    		$pdf->Cell(40,7,$col,1);
    	else
    		$pdf->Cell(20,7,$col,1);

    $pdf->Ln();
    
			$ctr = 0;
			$overallTotal = 0;
			while($productToday=mysqli_fetch_array($resultSoldToday))
			{
				$productTotal = $productToday['price']*$productToday['quantity'];
				$overallTotal += $productTotal;
				$pdf->Cell(40,6,date("Ymd",strtotime($productToday['dateadded']))."-".$productToday['id_cart'],1);
			    $pdf->Cell(40,6,$productToday['product_name'],1);
			    $pdf->Cell(20,6,$productToday['quantity'],1);
			    $pdf->Cell(40,6,number_format($productToday['price'],2),1);
			    $pdf->Cell(40,6,number_format($productTotal,2),1);
			    $pdf->Ln();
			    
			}
			 $pdf->Cell(40,6,"Overall Amount",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(20,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(40,6,number_format($overallTotal,2),1);
			    $pdf->Ln();
		?>

<?php
}else{
	$pdf->Cell(1,0,"No orders !");
}

$pdf->Output();

?>
