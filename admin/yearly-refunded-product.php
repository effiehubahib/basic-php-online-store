<?php
require('../lib/fpdf181/fpdf.php');
include_once("admin_config.php");

if(!isset($_GET['year'])){
	$currdate = date("Y");
	$queryRefundedYear = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.refunded as refunded, 
		      							tbl_cart_product.refund_amount as refund_amount
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE YEAR(tbl_cart.dateadded) = YEAR(NOW())  &&
		      						tbl_cart_product.refunded=1";
}else{
	$currdate = $_GET['year'];
	$queryRefundedYear = "SELECT tbl_product.product_name as product_name,
		      							tbl_cart_product.quantity as quantity,
		      							tbl_cart_product.price as price,
		      							tbl_cart_product.refunded as refunded, 
		      							tbl_cart_product.refund_amount as refund_amount
		      						FROM tbl_cart_product LEFT JOIN tbl_cart 
		      						ON tbl_cart_product.id_cart= tbl_cart.id_cart 
		      						LEFT JOIN tbl_product
		      						ON tbl_cart_product.id_product = tbl_product.id_product

		      						WHERE YEAR(tbl_cart.dateadded) = '".$_GET['year']."' &&
		      						tbl_cart_product.refunded=1";

}

$resultRefundedYear = mysqli_query($conn,$queryRefundedYear);


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(70);
$pdf->Cell(0,20,'SBQ Furniture');
$pdf->Ln(22);
$pdf->Cell(40);
$pdf->SetFont('Arial','',12);
$pdf->Cell(1,0," Refunded Product of: Year ".$currdate);
$pdf->Ln(12);

if(mysqli_num_rows($resultRefundedYear)>0){
	$header = array('Product Name', 'Quantity', 'Unit Price', 'Deduction', 'Total');
	foreach($header as $col){
		if($col =='Total'){
			$pdf->Cell(30,7,$col,1);
		}else{
			$pdf->Cell(40,7,$col,1);
		}
	}
	$pdf->Ln();

			$ctr = 0;
			$overallTotal = 0;
			$totalDeduction = 0;
			while($productRefund=mysqli_fetch_array($resultRefundedYear))
			{
				$productTotal = $productRefund['price']*$productRefund['quantity'];
				$overallTotal += $productTotal;
				$totalDeduction += $productRefund['refund_amount'];
			    $pdf->Cell(40,6,$productRefund['product_name'],1);
			    $pdf->Cell(40,6,$productRefund['quantity'],1);
			    $pdf->Cell(40,6,number_format($productRefund['price'],2),1);
			    $pdf->Cell(40,6,number_format($productRefund['refund_amount'],2),1,0,'R');
			    $pdf->Cell(30,6,number_format($productTotal,2),1,0,'R');
			    $pdf->Ln();
			    
			}
			 $pdf->Cell(40,6,"Overall Amount",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(40,6,"",1);
			    $pdf->Cell(40,6,number_format($totalDeduction,2),1,0,'R');
			    $pdf->Cell(30,6,number_format($overallTotal,2),1,0,'R');
			    $pdf->Ln();
}else{
	$pdf->Cell(1,0,"No refunded product!");
}

$pdf->Output();

?>
