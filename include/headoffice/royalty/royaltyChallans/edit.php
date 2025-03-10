<?php
// Get Challan No
$challanNo = '';
if(isset($_GET['id'])){$challanNo = $_GET['id'];}
//-----------------------------------------------
// Check Challan Exist
$sqllmscheck  = $dblms->querylms("SELECT f.id, f.status, f.id_month, f.challan_no, f.issue_date, f.due_date, f.total_amount, s.royalty_type, s.id_campus
                                    FROM ".FEES." f
                                    INNER JOIN ".ROYALTY_SETTING." s ON s.id_campus = f.id_campus
                                    WHERE status != '1' AND id_type = '3' 
                                    AND challan_no = '".cleanvars($challanNo)."' LIMIT 1");
if(!empty($challanNo) && (mysqli_num_rows($sqllmscheck) == 1)){

    $valRoyaltyCheck = mysqli_fetch_array($sqllmscheck);
    $campus = $valRoyaltyCheck['id_campus'];
                                        								
    echo'
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-edit"></i> Update Royalty Challan</h2>
        </header>
        <div class="panel-body">
            <form action="#" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <input type="hidden" name="id_challan" id="id_challan" value="'.$valRoyaltyCheck['id'].'">
                <input type="hidden" name="challan_no" id="challan_no" value="'.$challanNo.'">
                <fieldset>
                    <div class="panel-body">';	
                        //--------------- Royalty Particulars ----------------
                        $sqllmsParticulars = $dblms->querylms("SELECT part_id, part_name
                                                                    FROM ".ROYALTY_PARTICULARS."
                                                                    WHERE part_status = '1' AND is_deleted != '1'
                                                                    ORDER BY part_id ASC");
                        //-----------------------------------------------------
                        if(mysqli_num_rows($sqllmsParticulars) > 0){
                            
                            if($valRoyaltyCheck['royalty_type'] == 2){
                                echo'
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed table-striped mb-none">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Title</th>
                                                <th>Students</th>
                                                <th>Amount</th>
                                                <th class="center">Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                            $srno = 0;
                                            $grandTotal = 0;
                                            $totalAmount = 0;
                                            while($valPart = mysqli_fetch_array($sqllmsParticulars)){
                                                // //------------------ Campus Royalty -------------------
                                                $sqllmsRoyalty	= $dblms->querylms("SELECT d.detail_id, d.id_particular, d.id_class, d.no_of_std, d.amount_per_std, d.tuitionfee_percentage, d.total_amount,
                                                                                        c.class_id, c.class_name
                                                                                    FROM ".ROYALTY_CHALLAN_DET." d
                                                                                    LEFT JOIN ".CLASSES." c ON c.class_id = d.id_class
                                                                                    WHERE d.id_setup = '".$valRoyaltyCheck['id']."'
                                                                                    AND d.id_particular = '".$valPart['part_id']."' 
                                                                                    ORDER BY d.detail_id ASC");
                                                
                                                $valRoyalty = mysqli_fetch_array($sqllmsRoyalty);
                                                //-----------------------------------------------------
                                                $srno++;
                                                //--------------------------------------------------
                                                if($valPart['part_id'] == 1) {
                    
                                                    $stdSrno = 0;
                                                    echo ' 
                                                    <tr>
                                                        <td class="center" width="50">'.$srno.'</td>
                                                        <th>'.$valPart['part_name'].'</th>
                                                        <td>
                                                            <table class="table table-bordered table-condensed table-striped mb-none">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="center">#</th>
                                                                        <th>Class</th>
                                                                        <th>Students</th>
                                                                        <th>Percentage</th>
                                                                        <th>Amount</th>
                                                                        <th class="center">Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                    //------------------ Campus Royalty -------------------
                                                                    $sqllmsClasses = $dblms->querylms("SELECT class_id, class_name
                                                                                                            FROM ".CLASSES." c
                                                                                                            WHERE c.class_status = '1' AND c.is_deleted != '1'");
                                                                    while($valClass = mysqli_fetch_array($sqllmsClasses)){
                
                                                                        //Royalty Detail
                                                                        $sqllmsRoyaDet	= $dblms->querylms("SELECT no_of_std, amount_per_std, tuitionfee_percentage, total_amount
                                                                                                                FROM ".ROYALTY_CHALLAN_DET."
                                                                                                                WHERE id_setup = '".$valRoyaltyCheck['id']."'
                                                                                                                AND id_class='".$valClass['class_id']."' LIMIT 1");
                                                                        if(mysqli_num_rows($sqllmsRoyaDet)>0) { 
                                                                            $valAmountStd = mysqli_fetch_array($sqllmsRoyaDet);
                                                                            $no_of_std = $valAmountStd['no_of_std'];
                                                                            $amount = $valAmountStd['amount_per_std'];
                                                                            $total_amount = $valAmountStd['total_amount'];
                                                                            $tuitionfee_per = $valAmountStd['tuitionfee_percentage'];
                                                                        }
                                                                        else{
                                                                            $no_of_std = '';
                                                                            $amount = '';
                                                                            $total_amount = '';
                                                                            $tuitionfee_per = '';
                                                                        }
                
                                                                        //Students Of Specific Class
                                                                        $sqllmsStd = $dblms->querylms("SELECT COUNT(std_id) as students
                                                                                                            FROM ".STUDENTS." 
                                                                                                            WHERE id_campus = '".$valRoyaltyCheck['id_campus']."'
                                                                                                            AND id_class = '".$valClass['class_id']."'
                                                                                                            AND std_status = '1' AND is_deleted != '1'");
                                                                        $valStd = mysqli_fetch_array($sqllmsStd);
                                                                        $stdSrno ++;
                                                                        echo'
                                                                        <tr>
                                                                            <td class="center" width="50">'.$stdSrno.'</td>
                                                                            <th>'.$valClass['class_name'].'</th>
                                                                            <td width="100" class="center">
                                                                                <input type="number" class="form-control stds" required name="stds[]" id="stds'.$stdSrno.'" value="'.$no_of_std.'"/>
                                                                            </td>
                                                                            <td width="100" class="center"> 
                                                                                <input type="number" class="form-control percentage" required name="tuitionfee_percentage[]" id="tuitionfee_percentage'.$stdSrno.'" min="0" max="100" placeholder="Percentage" value="'.$tuitionfee_per.'" oninput="get_tuitionfee_percentage'.$stdSrno.'(this.value)"/>
                                                                            </td>
                                                                            <td width="100" class="center"> 
                                                                                <div id="get_value'.$stdSrno.'">
                                                                                    <input type="number" class="form-control amount" required name="amount[]" id="amount'.$stdSrno.'"  placeholder="Amount" value="'.$amount.'" readonly/>
                                                                                </div>
                                                                            </td>
                                                                            <td width="100" class="center">
                                                                                <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                                                                <input type="hidden" name="id_class[]" id="id_class'.$stdSrno.'"  value="'.$valClass['class_id'].'">
                                                                                <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount'.$stdSrno.'" value="'.$total_amount.'" readonly/>
                                                                            </td>
                                                                        </tr>
                
                                                                        <script type="text/javascript">
                
                                                                            //Return Tuition Fee
                                                                            function get_tuitionfee_percentage'.$stdSrno.'(tuitionfee_percentage) {  
                                                                                var id_class'.$stdSrno.' = document.getElementById("id_class'.$stdSrno.'").value;
                                                                                $.ajax({  
                                                                                    type: "POST",  
                                                                                    url: "include/ajax/get_tuitionfee.php",
                                                                                    data: { percentage: tuitionfee_percentage, camp: '.$campus.', cls: id_class'.$stdSrno.', srno: '.$stdSrno.' },
                                                                                    success: function(msg){  
                                                                                        $("#get_value'.$stdSrno.'").html(msg); 
                                                                                        $("#loading").html(""); 
                                                                                        
                                                                                        //Calculate Total Amount
                                                                                        var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                                                        var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                                                        totalAmount = stds *  amount;
                                                                                        $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                                                        
                                                                                        //Grand Total
                                                                                        var grandTotal = 0;
                                                                                        $(".totalAmount").each(function(){
                                                                                            grandTotal += +$(this).val();
                                                                                        });
                                                                                        $("#grandTotal").val(grandTotal);
                                                                                    }
                                                                                });  
                                                                            }
                                                                            
                                                                            //Calculate Total Amount
                                                                            $(document).on("load", "#amount'.$stdSrno.'", function() {
                                                                                var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                                                var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                                                totalAmount = stds *  amount;
                                                                                $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                                                
                                                                                //Grand Total
                                                                                var grandTotal = 0;
                                                                                $(".totalAmount").each(function(){
                                                                                    grandTotal += +$(this).val();
                                                                                });
                                                                                $("#grandTotal").val(grandTotal);
                                                                            }); 
                                                                                
                                                                            
                                                                            //Calculate Total Amount
                                                                            $(document).on("input", "#stds'.$stdSrno.'", function() {
                                                                                var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                                                var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                                                totalAmount = stds *  amount;
                                                                                $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                                                
                                                                                //Grand Total
                                                                                var grandTotal = 0;
                                                                                $(".totalAmount").each(function(){
                                                                                    grandTotal += +$(this).val();
                                                                                });
                                                                                $("#grandTotal").val(grandTotal);
                                                                            });
                                                                        </script>';
                                                                    }
                                                                    echo'
                                                                <tbody>
                                                            </table>
                                                        <td>
                                                    </tr>';
                                                    
                                                } else {
                    
                                                    echo'
                                                    <tr>
                                                        <td class="center" width="50">'.$srno.'</td>
                                                        <th colspan="3">'.$valPart['part_name'].'</th>
                                                        <td class="center">';
                                                            if(mysqli_num_rows($sqllmsRoyalty) > 0){
                                                                echo'<input type="hidden" name="detail_id[]" id="detail_id"  value="'.$valRoyalty['detail_id'].'">'; }
                                                            echo'
                                                            <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                                            <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount[]" value="'.$valRoyalty['total_amount'].'"/>
                                                        </td>
                                                    </tr>';
                                                }
                                            }
                                            echo'
                                        </tbody>
                                    </table>
                                    <h5><b>Grand Total</b></h5>	
                                    <div class="center">
                                        <input class="form-control" type="number" class="form-control" name="grandTotal" id="grandTotal" value="'.$valRoyaltyCheck['total_amount'].'" readonly/>
                                    </div>
                                </div>';
                            } else if($valRoyaltyCheck['royalty_type'] == 1) {
                                echo'
                                <div class="table-responsive">
                                    <table class="table table-bordered table-condensed table-striped mb-none">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Title</th>
                                                <th class="center"> Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                            $srno = 0;
                                            $grandTotal = 0;
                                            $totalAmount = 0;
                                            while($valPart = mysqli_fetch_array($sqllmsParticulars)){
                                                // //------------------ Campus Royalty -------------------
                                                // //------------------ Campus Royalty -------------------
                                                $sqllmsRoyalty	= $dblms->querylms("SELECT d.detail_id, d.id_particular, d.id_class, d.no_of_std, d.amount_per_std, d.tuitionfee_percentage, d.total_amount,
                                                                                        c.class_id, c.class_name
                                                                                    FROM ".ROYALTY_CHALLAN_DET." d
                                                                                    LEFT JOIN ".CLASSES." c ON c.class_id = d.id_class
                                                                                    WHERE d.id_setup = '".$valRoyaltyCheck['id']."'
                                                                                    AND d.id_particular = '".$valPart['part_id']."' 
                                                                                    ORDER BY d.detail_id ASC");
                                                
                                                $valRoyalty = mysqli_fetch_array($sqllmsRoyalty);
                                                //-----------------------------------------------------
                                                $srno++;
                                                //--------------------------------------------------
                                                if($valPart['part_id'] == 1) {
                    
                                                    $stdSrno = 0;
                                                    echo ' 
                                                    <tr>
                                                        <td class="center" width="50">'.$srno.'</td>
                                                        <th>'.$valPart['part_name'].'</th>
                                                        <td  colspan="2">
                                                            <table class="table table-bordered table-condensed table-striped mb-none">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="center">#</th>
                                                                        <th>Class</th>
                                                                        <th>Students</th>
                                                                        <th>Amount</th>
                                                                        <th class="center">Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                    //------------------ Campus Royalty -------------------
                                                                    $sqllmsClasses = $dblms->querylms("SELECT class_id, class_name
                                                                                                            FROM ".CLASSES." c
                                                                                                            WHERE c.class_status = '1' AND c.is_deleted != '1'");
                                                                    while($valClass = mysqli_fetch_array($sqllmsClasses)){
                
                                                                        //Royalty Detail
                                                                        $sqllmsRoyaDet	= $dblms->querylms("SELECT no_of_std, amount_per_std, total_amount
                                                                                                                FROM ".ROYALTY_CHALLAN_DET."
                                                                                                                WHERE id_setup = '".$valRoyaltyCheck['id']."'
                                                                                                                AND id_class='".$valClass['class_id']."' LIMIT 1");
                                                                        if(mysqli_num_rows($sqllmsRoyaDet)>0) { 
                                                                            $valAmountStd = mysqli_fetch_array($sqllmsRoyaDet);
                                                                            $no_of_std = $valAmountStd['no_of_std'];
                                                                            $amount = $valAmountStd['amount_per_std'];
                                                                            $total_amount = $valAmountStd['total_amount'];
                                                                        }
                                                                        else{
                                                                            $no_of_std = '';
                                                                            $amount = '';
                                                                            $total_amount = '';
                                                                        }
                
                                                                        //Students Of Specific Class
                                                                        $sqllmsStd = $dblms->querylms("SELECT COUNT(std_id) as students
                                                                                                            FROM ".STUDENTS." 
                                                                                                            WHERE id_campus = '".$valRoyaltyCheck['id_campus']."'
                                                                                                            AND id_class = '".$valClass['class_id']."'
                                                                                                            AND std_status = '1' AND is_deleted != '1'");
                                                                        $valStd = mysqli_fetch_array($sqllmsStd);
                                                                        $stdSrno ++;
                                                                        echo'
                                                                        <tr>
                                                                            <td class="center" width="50">'.$stdSrno.'</td>
                                                                            <th>'.$valClass['class_name'].'</th>
                                                                            <td width="100" class="center">
                                                                                <input type="number" class="form-control stds" required name="stds[]" id="stds'.$stdSrno.'" value="'.$no_of_std.'"/>
                                                                            </td>
                                                                            <td width="100" class="center"> 
                                                                                <input type="number" class="form-control amount" required name="amount[]" id="amount'.$stdSrno.'"  placeholder="Amount" value="'.$amount.'"/>
                                                                            </div>
                                                                            <td width="100" class="center">
                                                                                <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                                                                <input type="hidden" name="id_class[]" id="id_class"  value="'.$valClass['class_id'].'">
                                                                                <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount'.$stdSrno.'" value="'.$total_amount.'" readonly/>
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                        <script type="text/javascript">
                                                                            //Calculate Total Amount
                                                                            $(document).on("input", "#amount'.$stdSrno.'", function() {
                                                                                var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                                                var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                                                totalAmount = stds *  amount;
                                                                                $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                                                
                                                                                //Grand Total
                                                                                var grandTotal = 0;
                                                                                $(".totalAmount").each(function(){
                                                                                    grandTotal += +$(this).val();
                                                                                });
                                                                                $("#grandTotal").val(grandTotal);
                                                                            });
                                                                            
                                                                            //Calculate Total Amount
                                                                            $(document).on("input", "#stds'.$stdSrno.'", function() {
                                                                                var stds = document.getElementById("stds'.$stdSrno.'").value;
                                                                                var amount = document.getElementById("amount'.$stdSrno.'").value;
                                                                                totalAmount = stds *  amount;
                                                                                $("#totalAmount'.$stdSrno.'").val(totalAmount);
                                                                
                                                                                //Grand Total
                                                                                var grandTotal = 0;
                                                                                $(".totalAmount").each(function(){
                                                                                    grandTotal += +$(this).val();
                                                                                });
                                                                                $("#grandTotal").val(grandTotal);
                                                                            });
                                                                        </script>';
                                                                    }
                                                                    echo'
                                                                <tbody>
                                                            </table>
                                                        <td>
                                                    </tr>';
                                                    
                                                } else {
                                                    
                                                    echo'
                                                    <tr>
                                                        <td class="center" width="50">'.$srno.'</td>
                                                        <th >'.$valPart['part_name'].'</th>
                                                        <td class="center">';
                                                            if(mysqli_num_rows($sqllmsRoyalty) > 0){
                                                                echo'<input type="hidden" name="detail_id[]" id="detail_id"  value="'.$valRoyalty['detail_id'].'">'; }
                                                            echo'
                                                            <input type="hidden" name="id_particular[]" id="id_particular"  value="'.$valPart['part_id'].'">
                                                            <input type="number" class="form-control totalAmount" required name="totalAmount[]" id="totalAmount[]" value="'.$valRoyalty['total_amount'].'"/>
                                                        </td>
                                                    </tr>';
                                                }
                                            }
                                            echo'
                                        </tbody>
                                    </table>
                                    <h5><b>Grand Total</b></h5>	
                                    <div class="center">
                                        <input class="form-control" type="number" class="form-control" name="grandTotal" id="grandTotal" value="'.$valRoyaltyCheck['total_amount'].'" readonly/>
                                    </div>
                                </div>';
                            } else {
                                
                            }
                        }
                        else{
                            echo'<h4 class="text text-danger center">No Royalty Particular Added!</h4>';
                        }
                    echo'
                    </div>
                </fieldset>
                <div class="panel-footer row center" style="margin-bottom: -15px;">
                    <button type="submit"  name="update_challan" id="update_challan" class="btn btn-primary">Update Royalty Challan</button>
                </div>
            </form>
        </div>
    </section>';
} else{
    echo'
    <div class="panel-body">
        <h2 class="text text-danger text-center">No Record Found.</h2>
    </div>';
}
?>

<script type="text/javascript">
	//Grand Total Amount
	$(document).on("input", ".totalAmount", function() {
		var grandTotal = 0;
		$(".totalAmount").each(function(){
			grandTotal += +$(this).val();
		});
		$("#grandTotal").val(grandTotal);
	});
</script>