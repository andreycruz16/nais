<?php
    include('session.php');
    require '../../database.php';  


 ?>

<!DOCTYPE html>
<html lang="en">
<?php include("includes/header.php") ?>
<style>
 #recentStockDetails .modal-header {
      background-color: #3c8dbc;
      color: #fff;
      font-weight: bold;
      text-align: center;
 }    
</style>
<body>
    <div id="wrapper">
        <!-- TOP NAVIGATION -->
        <?php include("includes/topNavigation.php") ?>
        <!-- SIDE NAVIGATION -->
        <?php include("includes/sideNavigation.php") ?>

        <!-- WRAPPER START  -->
        <!-- WRAPPER START  -->
        <!-- WRAPPER START  -->

		<div id="page-wrapper">
            <div class="header"> 
                <h2 class="page-header">
                    <code class="text-success">REPORTS</code>
                </h2>
                <ol class="breadcrumb">
                    <li class="active">Reports</li>
                </ol>
            </div>
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                            <a href="warehouseReport.php" target="_blank" class="btn btn-success btn-md"><b>PHYSICAL COUNT OF ALL ITEMS</b> <br><span class="glyphicon glyphicon glyphicon-print"></span>&nbsp;&nbsp;<?php echo  date("F d, Y"); ?>&nbsp;&nbsp;<span class="glyphicon glyphicon glyphicon-print"></span></a>
                        <!-- <button class="btn btn-success btn-lg"> -->
                            <!-- <span class="glyphicon glyphicon-print"></span>&nbsp; <?php  echo ' PHYSICAL COUNT OF ALL ITEMS (' . date("F d, Y") . ')'; ?> -->
                        <!-- </button> -->
                    </div>
                </div><br>
                <div class="row">
                    <!-- SPECIFIC ITEM REPORTS -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Specific Item Reports
                            </div>
                            <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-condensed table-hover" id="specificItemReport">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" bgcolor="#f2ba7f" width="">Description</th>
                                                    <th class="text-center" bgcolor="#f2ba7f" width="">Part&nbsp;#</th>
                                                    <th class="text-center" bgcolor="#f2ba7f" width="">Quantity</th>
                                                    <!-- <th class="text-center" bgcolor="#f2ba7f" width="">Minimum Stock Count</th> -->
                                                    <th class="text-center" bgcolor="#f2ba7f" width="">Action</th> 
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th class="text-center" bgcolor="#f2ba7f" width="">Description</th>
                                                    <th class="text-center" bgcolor="#f2ba7f" width="">Part&nbsp;#</th>
                                                    <th class="text-center" bgcolor="#f2ba7f" width="">Quantity</th>
                                                    <!-- <th class="text-center" bgcolor="#f2ba7f" width="">Minimum Stock Count</th> -->
                                                    <td class="text-center" bgcolor="#f2ba7f" width=""></td> 
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                            <?php 
                                                require '../../database.php';
                                                $sql = "SELECT
                                                          tbl_warehouse.stock_id,
                                                          tbl_warehouse.description,
                                                          tbl_warehouse.partNumber,
                                                          tbl_warehouse.quantity,
                                                          tbl_warehouse.minStockCount
                                                        FROM
                                                          tbl_warehouse
                                                        ORDER BY
                                                          tbl_warehouse.description;";
                                                                                                                
                                                // echo $sql;
                                                $result = mysqli_query($conn, $sql);
                                                if (mysqli_num_rows($result) > 0) {
                                                    while($row = mysqli_fetch_array($result, MYSQL_NUM)) { 
                                                        $stock_id = $row[0];
                                                        $description = $row[1];
                                                        $partNumber = $row[2];
                                                        $quantity = $row[3];
                                                        $minStockCount = $row[4];
                                            ?>
                                                <tr>
                                                    <td><?php echo $description; ?></td>
                                                    <td><?php echo $partNumber; ?></td>
                                                    <td class="text-center"><?php echo $quantity; ?></td>
                                                    <!-- <td class="text-center"><?php echo $minStockCount; ?></td> -->
                                                    <td class="text-center">
                                                        <a href="generateReport.php?stock_id=<?php echo $stock_id; ?>&amp;partNumber=<?php echo $partNumber; ?>&amp;description=<?php echo $description; ?>&amp;quantity=<?php echo $quantity; ?>" class="btn btn-primary btn-xs">Generate Report <span class="glyphicon glyphicon-list-alt"></span></a>
                                                    </td>
                                                </tr>
                                            <?php 
                                                    }
                                                }
                                                    mysqli_close($conn);
                                            ?>                                          
                                            </tbody>                                            
                                        </table>
                                    </div>                                                
                            </div>
                        </div>
                    </div>                   
                </div>                    
				<?php include("includes/footer.php") ?>
            </div>
        </div>

        <!-- WRAPPER END  -->
        <!-- WRAPPER END  -->
        <!-- WRAPPER END  -->
    </div>

    <!-- STOCK IN MODAL -->                                                     
    <div id="stockIn" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-center">
                <div class="fetched-data-stockInModal"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>  

    <?php include("includes/scripts.php") ?>
    <script>
        $(document).ready(function(){
            $('#stockIn').on('show.bs.modal', function (e) {
                var stock_id = $(e.relatedTarget).data('id');
                $.ajax({
                    type : 'post',
                    url : 'phpScripts/fetch_stockInModal.php', //Here you will fetch records 
                    data :  'stock_id=' + stock_id, //Pass $id
                    success : function(data){
                    $('.fetched-data-stockInModal').html(data);//Show fetched data from database
                    }
                });
             });
        });
            
            $(document).ready(function () {
                $('#specificItemReport').dataTable({
                'iDisplayLength': 15, 
                'lengthMenu': [ [15, 25, 50, 100, -1], [15, 25, 50, 100, 'All'] ],
                'bSort': false
                 });
            });

            $(document).ready(function() {
                var table = $('#specificItemReport').DataTable();
             
                $("#specificItemReport tfoot th").each( function ( i ) {
                    var select = $('<select><option value=""></option></select>')
                        .appendTo( $(this).empty() )
                        .on( 'change', function () {
                            table.column( i )
                                .search( $(this).val() )
                                .draw();
                        } );
             
                    table.column( i ).data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );           
            } );             

            $('.form_date').datetimepicker({
                // language:  'fr',
                format:'yyyy-mm-dd',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                minView: 2,
                forceParse: 0
            });


            function printTable() { 
                popupWindow = window.open('table-print/reportSummary.php',"_blank","directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=950, height=600,top=200,left=200");
            }                
    </script>
</body>
</html>