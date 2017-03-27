<?php
//Include database connection
require '../../../database.php';
if($_POST['stock_id']) {
    $stock_id = $_POST['stock_id']; //escape string
    // Run the Query
    $sql = "SELECT * FROM tbl_warehouse WHERE stock_id = ".$stock_id.";";
    $result = mysqli_query($conn, $sql);
    // Fetch Records
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_array($result, MYSQL_NUM)) { 
            $stock_id = $row[0];
            $date = $row[1];
            $description = $row[2];
            $reference_id = $row[3];
            $referenceNumber = $row[4];
            $partNumber = $row[5];
            $boxNumber = $row[6];
            $quantity = $row[7];
            $customerName = $row[8];
            $model = $row[9];
            $serialNumber = $row[10];
            $minStockCount = $row[11];
            $transferType_id = $row[12];
		}
	}
    // Echo the data you want to show in modal
 } else {
    header("Location: ../index.php"); // Redirecting to All Records Page
 }
?>
<div class="modal-header modal-danger">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3 class="modal-title"><strong>IN</strong></h3>
    <h4 class="modal-title"><strong>Update Stock: "<?php echo $description; ?>" (<?php echo $partNumber; ?>)</strong></h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">               
            <div class="form-group">
                <label class="text-danger"><span class="glyphicon glyphicon-star" aria-hidden="true"></span></label><strong>Required</strong>
                <label class="text-primary"><span class="glyphicon glyphicon-star" aria-hidden="true"></span></label><strong>Optional</strong>
            </div>
            <form role="form" class="form-horizontal" action="phpScripts/stockIn.php" method="post">  
                <div class="input-group col-md-12">
                    <span class="input-group-addon" id="basic-addon1"><label class="text-danger"><span class="glyphicon glyphicon-star" aria-hidden="true"></span></label> Date:</span>
                    <div class="input-group date form_date col-md-12">
                        <input id="date" name="date" class="form-control" type="date" value="<?php echo date('Y-m-d'); ?>" placeholder="YYYY-MM-DD" required>
                    </div>
                </div>
                <br><br>
                <div class="input-group col-md-12">
                    <span class="input-group-addon" id="basic-addon1"><label class="text-danger"><span class="glyphicon glyphicon-star" aria-hidden="true"></span></label> Reference #:</span>
                    <select class="form-control" name="reference_id" id="reference_idModal">
                        <option value="1">Purchase Order (Unit)</option>
                        <option value="2">Purchase Order (Parts)</option>
                        <option value="3">Transfer Ticket</option>
                        <option value="4">Pick-up Order</option>
                        <option value="5">Delivery Receipt</option>
                    </select>
                    <input type="text" name="referenceNumber" class="form-control" id="referenceNumberModal" placeholder="Reference Number" aria-describedby="basic-addon1" required autofocus autocomplete="off">
                    <input type="text" name="receivingReport" class="form-control" id="receivingReportModal" placeholder="Receiving Report" aria-describedby="basic-addon1" autofocus autocomplete="off">
                </div>
                <br><br>
                <h5><strong><?php echo $description; ?> Current Quantity: <span class="text-success"><?php echo $quantity; ?></span></strong></h5>
                <div class="input-group col-md-12">
                    <span class="input-group-addon" id="basic-addon1"><label class="text-danger"><span class="glyphicon glyphicon-star" aria-hidden="true"></span></label> Quantity (IN):</span>
                    <input type="number" name="quantity" class="form-control" id="quantity" placeholder="0" aria-describedby="basic-addon1" required autofocus autocomplete="off">
                </div>
                <input type="hidden" name="description" id="description" value="<?php echo $description; ?>"> 
                <input type="hidden" name="partNumber" id="partNumber" value="<?php echo $partNumber; ?>"> 
                <input type="hidden" name="transferType_id" id="transferType_id" value="2">
                <input type="hidden" name="stock_id" id="stock_id" value="<?php echo $stock_id; ?>"> 
                <br><br><br>                                                                     
                <button type="submit" class="btn btn-primary col-md-6 col-md-offset-3"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
            </form>   
        </div>  
        <div class="col-md-1"></div>        
    </div>
</div>

<script>
            $('#reference_idModal').change(function(event) {
                if($(this).val() == '1' || $(this).val() == '2') {
                    $('#receivingReportModal').fadeIn().val("");
                } else {
                    $('#receivingReportModal').fadeOut().val("N/A");
                }
            });    
</script>