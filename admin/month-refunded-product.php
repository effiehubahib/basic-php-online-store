<?php
require('../lib/fpdf181/fpdf.php');
include_once("admin_config.php");


if(!isset($_GET['month'])){
	$currdate = date("F Y");
	$queryRefundMonth = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE MONTH(tbl_cart.dateadded) = MONTH(NOW()) &&
		      						tbl_cart_product.refunded=1";
}else{
	$currdate = date("F Y",strtotime($_GET['month']));
	$queryRefundMonth = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart.dateadded as dateadded,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.id_cart as id_cart
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE MONTH(tbl_cart.dateadded) = MONTH('".$_GET['month']."-1') &&
		      						tbl_cart_product.refunded=1";

}
$resultRefundToday = mysqli_query($conn,$queryRefundMonth);


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(70);
$pdf->Cell(0,20,'SBQ Furniture');
$pdf->Ln(22);
$pdf->Cell(40);
$pdf->SetFont('Arial','',12);
$pdf->Cell(1,0,"Today's Sold Product: ".$currdate );
$pdf->Ln(12);

    
if(mysqli_num_rows($resultRefundToday)>0){
	$header = array('Product Name', 'Quantity', 'Unit Price', 'Deduction', 'Total');
 	foreach($header as $col)
 		if($col!='Quantity')
    		$pdf->Cell(40,7,$col,1);
    	else
    		$pdf->Cell(20,7,$col,1);
    	
    $pdf->Ln();
    
			$ctr = 0;
			$overallTotal = 0;
			$totalDeduction = 0;
			while($productRefund=mysqli_fetch_array($resultRefundedToday))
			{
				$productTotal = $productRefund['price']*$productRefund['quantity'];
				$overallTotal += $productTotal;
				$totalDeduction += $productRefund['refund_amount'];
			    $pdf->Cell(40,6,$productRefund['product_name'],1);
			    $pdf->Cell(40,6,$productRefund['quantity'],1);
			    $pdf->Cell(40,6,number_format($productRefund['price'],2),1);
			    $pdf->Cell(40,6,number_format($productRefund['refund_amount'],2),1);
			    $pdf->Cell(40,6,number_format($productTotal,2),1);
			    $pdf->Ln();
			    
			}
			 $pdf->Cell(40,6,"Overall Amount",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(40,6,number_format($totalDeduction,2),1);
			    $pdf->Cell(40,6,number_format($overallTotal,2),1);
			    $pdf->Ln();
		?>

<?php
}else{
	$pdf->Cell(1,0,"No orders today!");
}

$pdf->Output();

?>
