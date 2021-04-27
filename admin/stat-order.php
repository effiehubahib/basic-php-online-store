<?php

include_once("admin-config.php");
include_once("admin-header.php");
$month = date("m");
$currentYr = date("Y");

$addedStocksData = array();
$monthlyStockAddedForecast = array();
$stockDataFormat ='';
$stockDataForecastFormat = '';

$queryStatOrder = "SELECT SUM(cp.quantity * cp.price) as totalorder, MONTH(tc.dateadded) as ordermonth,YEAR(tc.dateadded) as orderyear FROM tbl_cartproduct cp LEFT JOIN tbl_cart tc ON cp.cart_id = tc.cart_id WHERE YEAR(tc.dateadded) = YEAR(NOW()) GROUP BY ordermonth, orderyear";


$resultStatOrder = mysqli_query($conn,$queryStatOrder);
$result = mysqli_query($conn,$queryStatOrder);
if(mysqli_num_rows($resultStatOrder)>0)
{
    while($row=mysqli_fetch_array($resultStatOrder))
    {
        $orderData[$row['ordermonth']] = $row['totalorder'];
    }
}
$monthlyOrderForecast = array();
$orderDataFormat ='';
$orderDataForecastFormat = '';

/*order data*/
for($x=1;$x<=12;$x++){

    if(array_key_exists($x,$orderData))
    {
        $value = $orderData[$x];
    }else{
        $value = 0;
    }
     $orderDataFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                totalorder: '.$value.'
            },';

    if($x<=$month){

        $monthlyOrderForecast[$x] = $value;
            $orderDataForecastFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                totalorder: '.$value.'
            },';

    }else{

        $monthlyOrderForecast[$x] = ($monthlyOrderForecast[$x-2] + $monthlyOrderForecast[$x-1])/2;
        $orderDataForecastFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                totalorder: "'.number_format($monthlyOrderForecast[$x],2,'.','').'"
            },';
    }
}
?>

 <div id="page-wrapper">

    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    <small>Monthly Orders</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->
        <table id="table" class="table table-striped table-bordered table-hover table-order-column" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: gray;">
                  <th>Month</th>
                  <th>Year</th>
                  <th>Total Orders</th>
                </tr>
            </thead>
            <tbody>
             <?php 

              while($order=mysqli_fetch_array($result))
              {
                
                 echo "<tr>";
                  echo "<td>".date("M",strtotime($order['orderyear']."-".$order['ordermonth']."-01"))."</td>";
                  echo "<td>".$order['orderyear']."</td>";
                  echo "<td>".$order['totalorder']."</td>";
                 echo "</tr>";
              }
             ?> 
             </tbody>
              <tfoot>
            </tfoot>
        </table>
        <div class="col-lg-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Monthly Orders <?php echo date("Y");?></h3>
                </div>
                <div class="panel-body">
                    <div id="morris-total-order"></div>
                   
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Monthly Orders Forecast <?php echo date("Y");?></h3>
                </div>
                <div class="panel-body">
                    <div id="morris-total-order-forecast"></div>
                   
                </div>
            </div>
        </div>


        
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>


<?php
include_once("admin-footer.php");
?>
<script type="text/javascript">
    // Morris.js Charts sample data for SB Admin template
$( document ).ready(function() {
    $(function() {

        
       // Bar Chart
        Morris.Bar({
            element: 'morris-total-order',
            data: [<?php echo $orderDataFormat;?>],
            xkey: 'month',
            ykeys: ['totalorder'],
            labels: ['Total Orders'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true
        });

        Morris.Bar({
            element: 'morris-total-order-forecast',
            data: [<?php echo $orderDataForecastFormat;?>],
            xkey: 'month',
            ykeys: ['totalorder'],
            labels: ['Forecast Total Orders'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true
        });
    });
});

</script>