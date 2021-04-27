    <?php

include_once("admin-config.php");
include_once("admin-header.php");

$month = date("m");
$currentYr = date("Y");

$querySupplier = "SELECT * FROM tbl_supplier ";
$resultSupplier = mysqli_query($conn,$querySupplier);

$queryReorder = "SELECT * FROM tbl_product WHERE quantity<=reorder_level";
$resultReorder = mysqli_query($conn,$queryReorder);

$queryOrder = "SELECT * FROM tbl_cart WHERE deleteStatus=0 AND delivery_status!=7";
$resultOrder = mysqli_query($conn,$queryOrder);

$queryStatOrder = "SELECT SUM(cp.quantity * cp.price) as totalorder, MONTH(tc.dateadded) as ordermonth,YEAR(tc.dateadded) as orderyear FROM tbl_cartproduct cp LEFT JOIN tbl_cart tc ON cp.cart_id = tc.cart_id WHERE YEAR(tc.dateadded) = YEAR(NOW()) GROUP BY ordermonth, orderyear";

$resultStatOrder = mysqli_query($conn,$queryStatOrder);
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
$overallOrder =0;

/*order data*/
for($x=1;$x<=12;$x++){
    if(array_key_exists($x,$orderData))
    {
        $value = $orderData[$x];
    }else{
        $value = 0;
    }
     $overallOrder += $value;
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

/*Added stock collect and formalize data*/
$queryStatAddedStock = "SELECT SUM(ta.stockquantity * tp.price) as totalstock, MONTH(ta.date_added) as stockmonth,YEAR(ta.date_added) as stockyear FROM tbl_addedstock ta LEFT JOIN tbl_product tp ON ta.product_id = tp.product_id WHERE YEAR(ta.date_added) = YEAR(NOW()) GROUP BY stockmonth, stockyear";






$addedStocksData = array();
$monthlyStockAddedForecast = array();
$stockDataFormat ='';
$stockDataForecastFormat = '';

$resultStatAddedStock = mysqli_query($conn,$queryStatAddedStock);
if(mysqli_num_rows($resultStatAddedStock)>0)
{
    while($row=mysqli_fetch_array($resultStatAddedStock))
    {
        $addedStocksData[$row['stockmonth']] = $row['totalstock'];
    }
}
//monthly added stocks current year


/* for added stocks */
for($x=1;$x<=12;$x++){
    if(array_key_exists($x,$addedStocksData))
    {
        $value = $addedStocksData[$x];
    }else{
        $value = 0;
    }
     $stockDataFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                addedstocks: '.$value.'
            },';

    if($x<=$month){
        $monthlyStockAddedForecast[$x] = $value;
            $stockDataForecastFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                addedstocks: '.$value.'
            },';
    }else{

        $monthlyStockAddedForecast[$x] = ($monthlyStockAddedForecast[$x-2] + $monthlyStockAddedForecast[$x-1])/2;
        
        $stockDataForecastFormat .='{
                month: "'.date("M",strtotime($currentYr."-".$x."-01")).'",
                addedstocks: "'.number_format($monthlyStockAddedForecast[$x],2,'.','').'"
            },';
    }


     //echo"<br/>X:".$x.":".$monthlyStockAddedForecast[$x];
}







?>
<script type="text/javascript">
	//$('datatableTodayOrder').dataTable({bFilter: false, });
</script>
<div id="admin" class="admin_dashboard">
 <div class="columns-container">
    <div id="columns" class="container">
    	<h3>Admin Dashboard</h3>
  		<div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-cart fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo mysqli_num_rows($resultOrder);?></div>
                                        <div>Orders!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="orders.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-tasks fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?php echo mysqli_num_rows($resultReorder);?></div>
                                        <div>Reorder!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="reorder-level.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                   
                     <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-support fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge">	
                                        <?php
                                        	echo mysqli_num_rows($resultSupplier);

                                        ?>
                                        </div>
                                        <div>Suppliers!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="suppliers.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
        </div>
        <div class="row">
                    <div class="col-lg-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Monthly Added Stocks <?php echo date("Y");?></h3>
                            </div>
                            <div class="panel-body">
                                <div id="morris-added-stocks"></div>
                                <div class="text-right">
                                    <a href="stat-added-stocks.php">View Details <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Monthly Added Stocks Forecast <?php echo date("Y");?></h3>
                            </div>
                            <div class="panel-body">
                                <div id="morris-added-stocks-forecast"></div>
                                <div class="text-right">
                                    <a href="stat-added-stocks.php">View Details <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel panel-yellow">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Monthly Total Order <?php echo date("Y");?></h3>
                            </div>
                            <div class="panel-body">
                                <div id="morris-total-order"></div>
                                <div class="text-right">
                                    <a href="stat-order.php">View Details <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-long-arrow-right"></i> Monthly Total Order Forecast <?php echo date("Y");?></h3>
                            </div>
                            <div class="panel-body">
                                <div id="morris-total-order-forecast"></div>
                                <div class="text-right">
                                    <a href="stat-order.php">View Details <i class="fa fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                   
        </div>
                <!-- /.row -->
    </div>
 </div>
</div>

<?php 

include_once("admin-footer.php");



?>
<script type="text/javascript">
    // Morris.js Charts sample data for SB Admin template
$( document ).ready(function() {
    $(function() {

        Morris.Bar({
            element: 'morris-total-order',
            data: [<?php echo $orderDataFormat;?>],
            xkey: 'month',
            ykeys: ['totalorder'],
            labels: ['Total Order '],
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
            labels: ['Forecast Total Order'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true
        });
       // Bar Chart
        Morris.Bar({
            element: 'morris-added-stocks',
            data: [<?php echo $stockDataFormat;?>],
            xkey: 'month',
            ykeys: ['addedstocks'],
            labels: ['Total Added Stocks'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true
        });

        Morris.Bar({
            element: 'morris-added-stocks-forecast',
            data: [<?php echo $stockDataForecastFormat;?>],
            xkey: 'month',
            ykeys: ['addedstocks'],
            labels: ['Forecast Total Stocks'],
            barRatio: 0.4,
            xLabelAngle: 35,
            hideHover: 'auto',
            resize: true
        });
    });
});

</script>