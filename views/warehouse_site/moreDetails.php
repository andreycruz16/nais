<?php
    include('session.php');
    require '../../database.php';  

    if (!empty($_GET['stock_id'])) {
        $stock_id = $_GET['stock_id'];

        $sql = "SELECT
                  tbl_warehouse.date,
                  tbl_warehouse.description,
                  tbl_reference.referenceType,
                  tbl_warehouse.referenceNumber,
                  tbl_warehouse.partNumber,
                  tbl_warehouse.boxNumber,
                  tbl_warehouse.quantity,
                  tbl_warehouse.customerName,
                  tbl_warehouse.model,
                  tbl_warehouse.serialNumber,
                  tbl_warehouse.minStockCount,
                  tbl_transfer_type.transferType,
                  tbl_warehouse.receivingReport
                FROM
                  tbl_warehouse
                INNER JOIN
                  tbl_reference ON tbl_warehouse.reference_id = tbl_reference.reference_id
                INNER JOIN
                  tbl_transfer_type ON tbl_warehouse.transferType_id = tbl_transfer_type.transferType_id
                WHERE
                  stock_id = ".$stock_id.";";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_array($result, MYSQL_NUM)) {    
                    $date = $row[0];
                    $description = $row[1];
                    $referenceType = $row[2];
                    $reference_number = $row[3];
                    $partNumber = $row[4];
                    $boxNumber = $row[5];
                    $quantity = $row[6];
                    $customerName = $row[7];
                    $model = $row[8];
                    $serialNumber = $row[9];
                    $minStockCount = $row[10];
                    $transferType = $row[11];
                    $receivingReport = $row[12];
            }
        } else {
            header("location: index.php");    
        }

    } else {
        header("location: index.php");
    }
 ?>

<!DOCTYPE html>
<html lang="en">
<?php include("includes/header.php") ?>
<style>
 #historyDetails .modal-header {
      background-color: #3c8dbc;
      color: #fff;
      font-weight: bold;
      text-align: center;
 }    

  #editHistoryDetails .modal-header {
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
	    <div id="page-wrapper">
        <div class="header"> 
            <h2 class="page-header">
                <code class="text-primary"><?php echo $description; ?> (<?php echo $partNumber; ?>)</code>
            </h2>
            <ol class="breadcrumb">
                <li><a href="index.php">All Records</a></li>
                <li class="active">Item Details</li>
            </ol>
        </div>
        <div id="page-inner">
            <div class="row">
              <div class="col-md-8 col-sm-8 col-xs-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Item Information (<?php echo $partNumber; ?>)
                    </div>
                    <div class="panel-body">
                        <form action="phpScripts/updateFixedDetails.php" method="post">
                        <br><b>Edit Information:</b><br><br>
                            <div class="form-group input-group col-md-12">
                              <div class="col-md-6">
                                  <div class="input-group">
                                      <span class="input-group-addon" id="basic-addon1">Part Number:</span>
                                      <input type="text" name="partNumber" id="partNumber" class="form-control" value="<?php echo $partNumber; ?>" aria-describedby="basic-addon1" required>                                                                                           
                                  </div>                                                                                                                                               
                               </div>
                               <div class="col-md-6">
                                  <div class="input-group">
                                      <span class="input-group-addon" id="basic-addon1">Description:</span>
                                      <input type="text" name="description" id="description" class="form-control" value="<?php echo $description; ?>" aria-describedby="basic-addon1" required>
                                  </div>                                                                                                                                               
                               </div>        
                            </div>
                            <div class="form-group input-group col-md-12">
                               <div class="col-md-6">
                                  <div class="input-group">
                                      <span class="input-group-addon" id="basic-addon1"><label class="text-danger"></label>Box Number:</span>
                                      <input type="text" name="boxNumber" id="boxNumber" class="form-control"  placeholder="Bo" value="<?php echo $boxNumber; ?>" aria-describedby="basic-addon1" autocomplete="off">                                                                                           
                                  </div>                                                                                                                                               
                               </div>
                              <div class="col-md-6">
                                  <div class="input-group">
                                      <span class="input-group-addon" id="basic-addon1">Quantity:</span>
                                      <input type="text" name="quantity" id="quantity" class="form-control" placeholder="Quantity" value="<?php echo $quantity; ?>" aria-describedby="basic-addon1" disabled readonly>                                                                                           
                                  </div>                                                                                                                                               
                               </div>      
                            </div>
                            <div class="form-group input-group col-md-12">
                              <div class="col-md-6">
                                  <div class="input-group">
                                      <span class="input-group-addon" id="basic-addon1">Minimum Stock Count:</span>
                                      <input type="text" name="minStockCount" id="minStockCount" class="form-control" value="<?php echo $minStockCount; ?>" aria-describedby="basic-addon1" required>
                                  </div>                                                                                                                                               
                               </div>
                            </div>
                            <input type="hidden" name="stock_id" id="stock_id" value="<?php echo $stock_id; ?>">   
                            <div class="form-group input-group col-md-12">
                               <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="index.php" class="btn btn-default">Cancel</a>
                               </div>                                    
                            </div>
                        </form>                         
                    </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        QR Code (<?php echo $partNumber; ?>)
                    </div>
                    <div class="panel-body">
                        <div class="form-group input-group col-md-12">
                            <div class="text-center">
                            <?php 
                                //set it to writable location, a place for temp generated PNG files
                                $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'QrCodes'.DIRECTORY_SEPARATOR;
                                $PNG_WEB_DIR = 'QrCodes/';

                                include "phpqrcode/qrlib.php";
                                
                                if (!file_exists($PNG_TEMP_DIR))
                                    mkdir($PNG_TEMP_DIR);

                                $qrValue = $partNumber;
                                $filename = $PNG_TEMP_DIR.$qrValue.'.png';
                                $errorCorrectionLevel = 'H';
                                $matrixPointSize = 10;
                                
                                QRcode::png($qrValue, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
                                   
                                //display generated file
                                echo '<img class="img-thumbnail" title="'.$qrValue.'" src="'.$PNG_WEB_DIR.basename($filename).'" />';  
                             ?>                                              
                            </div>                                    
                            <div class="text-center"><b>Right Click > Save image as.. > Save</b></div>
                        </div>
                    </div>
                </div>
              </div>
            </div>            
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Record History (<?php echo $partNumber; ?>)
                    </div>
                    <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-condensed table-hover" id="recordHistory">
                                    <thead>
                                        <tr>
                                            <th class="text-center" bgcolor="#e5e5e5" width="155">TIMESTAMP</th>
                                            <th class="text-center" bgcolor="#f2ba7f">Date&nbsp;(M/D/Y)</th>
                                            <th class="text-center" bgcolor="#f2ba7f">Reference&nbsp;Type</th>
                                            <th class="text-center" bgcolor="#f2ba7f">Reference&nbsp;#</th>
                                            <th class="text-center" bgcolor="#f2ba7f">Receiving&nbsp;Report</th>
                                            <th class="text-center" bgcolor="#f2ba7f">Transfer&nbsp;Type</th>
                                            <th class="text-center" bgcolor="#f2ba7f"width="30">+&nbsp;/&nbsp;−</th>
                                            <th class="text-center" bgcolor="#f2ba7f">Stock Count</th> <!-- quantity -->
                                            <th class="text-center" bgcolor="#f2ba7f">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" bgcolor="#e5e5e5" width="155">TIMESTAMP</th> 
                                            <th class="text-center" bgcolor="#f2ba7f">Date&nbsp;(M/D/Y)</th> 
                                            <th class="text-center" bgcolor="#f2ba7f">Reference&nbsp;Type</th> 
                                            <th class="text-center" bgcolor="#f2ba7f">Reference&nbsp;#</th> 
                                            <th class="text-center" bgcolor="#f2ba7f">Receiving&nbsp;Report</th>
                                            <th class="text-center" bgcolor="#f2ba7f">Transfer&nbsp;Type</th>
                                            <th class="text-center" bgcolor="#f2ba7f"width="30">+&nbsp;/&nbsp;−</th>
                                            <th class="text-center" bgcolor="#f2ba7f">Stock Count</th>  <!-- quantity -->
                                            <td class="text-center" bgcolor="#f2ba7f"></td> 
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                    <?php 
                                        require '../../database.php';
                                        $sql = "SELECT 
                                                tbl_warehouse_history.timestamp, 
                                                tbl_warehouse_history.date,
                                                tbl_reference.referenceType,
                                                tbl_warehouse_history.referenceNumber,
                                                tbl_warehouse_history.quantity,
                                                tbl_warehouse_history.quantityChange,
                                                tbl_transfer_type.transferType,
                                                tbl_users.username,
                                                tbl_warehouse_history.history_id,
                                                tbl_warehouse_history.receivingReport
                                                FROM tbl_warehouse_history 
                                                INNER JOIN tbl_reference
                                                ON tbl_warehouse_history.reference_id = tbl_reference.reference_id
                                                INNER JOIN tbl_transfer_type
                                                ON tbl_transfer_type.transferType_id = tbl_warehouse_history.transferType_id
                                                INNER JOIN tbl_users
                                                ON tbl_warehouse_history.user_id = tbl_users.user_id
                                                WHERE tbl_warehouse_history.stock_id = ".$stock_id."
                                                ORDER BY tbl_warehouse_history.history_id DESC;";
                                        // echo $sql;
                                        $result = mysqli_query($conn, $sql);
                                        if (mysqli_num_rows($result) > 0) {
                                            while($row = mysqli_fetch_array($result, MYSQL_NUM)) { 
                                            $timestamp = $row[0];
                                            $date = $row[1];
                                            $referenceType = $row[2];
                                            $referenceNumber = $row[3];
                                            $quantity = $row[4];
                                            $quantityChange = $row[5];
                                            $transferType = $row[6];
                                            $username = $row[7];
                                            $history_id = $row[8];
                                            $receivingReport = $row[9];
                                    ?>
                                        <tr>
                                            <td class="text-center" title="Date & Time Updated"><?php  echo date('m/d/Y | h:i:s A', strtotime($timestamp)); ?></td>
                                            <td class="text-center"><?php echo date('m/d/Y', strtotime($date)); ?></td>
                                            <td><?php echo $referenceType; ?></td>
                                            <td><?php echo $referenceNumber; ?></td>
                                            <td><?php echo $receivingReport; ?></td>
                                            <td class="text-center"><?php echo $transferType ?></td>
                                            <td class="text-center"><?php echo $quantityChange; ?></td>
                                            <td class="text-center"><?php echo $quantity; ?></td>
                                            <td class="text-center">
                                              <button type="button" class="btn btn-info btn-xs" title="Edit" data-toggle="modal" data-target="#editHistoryDetails" data-id="<?php echo $history_id; ?>">Edit <span class="glyphicon glyphicon-edit"></span></button>
                                              <button type="button" class="btn btn-primary btn-xs" title="Details" data-toggle="modal" data-target="#historyDetails" data-id="<?php echo $history_id; ?>">Details <span class="glyphicon glyphicon-list-alt"></span></button>                                                      
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
		    <?php include("includes/footer.php") ?>
        </div>
      </div>
    </div>

    <!-- EDIT HISTORY DETAILS -->                                                     
    <div id="editHistoryDetails" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="fetched-data-editHistoryDetailsModal"></div>
            </div>
        </div>
    </div>        

    <!-- HISTORY DETAILS -->                                                     
    <div id="historyDetails" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="fetched-data-historyDetailsModal"></div>
            </div>
        </div>
    </div> 

    <?php include("includes/scripts.php") ?>
    <script>
            $(document).ready(function () {
                $('#recordHistory').dataTable({
                'iDisplayLength': 15, 
                'lengthMenu': [ [15, 25, 50, 100, -1], [15, 25, 50, 100, 'All'] ],
                'bSort': false
                 });
            });

            $(document).ready(function() {
                var table = $('#recordHistory').DataTable();
             
                $("#recordHistory tfoot th").each( function ( i ) {
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

            if($('#transferType_id').val() == "IN") {
              $('#forTransferTypeOut').remove();
            }            

            // HISTORY DETAILS
            $(document).ready(function(){
                $('#historyDetails').on('show.bs.modal', function (e) {
                    var history_id = $(e.relatedTarget).data('id');
                    $.ajax({
                        type : 'post',
                        url : 'phpScripts/fetch_stockDetailsModal.php', //Here you will fetch records 
                        data :  'history_id=' + history_id, //Pass $id
                        success : function(data){
                        $('.fetched-data-historyDetailsModal').html(data);//Show fetched data from database
                        }
                    });
                 });
            });

          // EDIT HISTORY DETAILS
          $(document).ready(function(){
              $('#editHistoryDetails').on('show.bs.modal', function (e) {
                  var history_id = $(e.relatedTarget).data('id');
                  $.ajax({
                      type : 'post',
                      url : 'phpScripts/fetch_stockHistoryDetailsModal.php', //Here you will fetch records 
                      data :  'history_id=' + history_id, //Pass $id
                      success : function(data){
                      $('.fetched-data-editHistoryDetailsModal').html(data);//Show fetched data from database
                      }
                  });
               });
          });

    </script>
</body>
</html>