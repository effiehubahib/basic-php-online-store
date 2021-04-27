<?php
require('../lib/fpdf181/fpdf.php');
include_once("admin_config.php");

$deliverystatus=array("Waiting for payment","Waiting for management confirmation","Payment pending","Payment received",
      						 "Order being processed", "In delivery", "Delivered", "Order closed");

date_default_timezone_set(date_default_timezone_get());


if(!isset($_GET['week'])){
	$queryWeekOrder = "SELECT *,tbl_user.id as user_id FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE WEEK(tbl_cart.dateadded) = WEEK(NOW())";
	$currdate =date("W")." of ".date("Y");
	$weeknum = date("W");
	$year = date("Y");
}else{

	$queryWeekOrder = "SELECT *,tbl_user.id as user_id FROM tbl_cart LEFT JOIN tbl_user ON tbl_cart.id_user= tbl_user.id WHERE WEEK(tbl_cart.dateadded) = WEEK('".$_GET['week']."')";
	$currdate =date("W")." of ".date("Y",strtotime($_GET['week']));
	$weeknum = date("W",strtotime($_GET['week']));
	$year = date("Y",strtotime($_GET['week']));
}

$resultWeekOrder = mysqli_query($conn,$queryWeekOrder);


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(70);
$pdf->Cell(0,20,'SBQ Furniture');
$pdf->Ln(22);
$pdf->Cell(60);
$pdf->SetFont('Arial','',12);
$pdf->Cell(1,0,"Order in week #:".$weeknum." (".date('M d, Y',strtotime($year.'W'.$weeknum))." - ".date('M d, Y',strtotime($year.'W'.$weeknum." + 7 day")).")"); 
$pdf->Ln(12);
$pdf->Cell(50,6,"Customer's ID",0);
$pdf->Cell(60,6,"Customer's Name",0);
$pdf->Cell(60,6,"Delivery Status",0);
$pdf->Ln();
if(mysqli_num_rows($resultWeekOrder)>0){
			
			while($cartToday=mysqli_fetch_array($resultWeekOrder))
			{
				$pdf->Cell(50,6,$cartToday['user_id'],0);
				$pdf->Cell(60,6,$cartToday['lastname'].", ".$cartToday['firstname'],0);
			    $pdf->Cell(60,6,$deliverystatus[$cartToday['delivery_status']],0);
			    $pdf->Ln();
			}
		?>

<?php
}else{
	$pdf->Cell(1,0,"No orders today!");
}

$pdf->Output();

?>
