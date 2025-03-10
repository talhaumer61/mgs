<?php
//--------------------------------------------
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
//--------------------------------------------
if(isset($_POST['id_campus'])) 
{
	$id_campus = $_POST['id_campus']; 
    //-----------------------------------------------------
    $sqllmsParticulars	= $dblms->querylms("SELECT part_id, part_name, part_amount
                                        FROM ".ROYALTY_PARTICULARS."
                                        WHERE part_status = '1'  AND is_deleted != '1' ");
    //-----------------------------------------------------
    $srno = 0;
    $amount = 0;
    $total_amount = 0;
    //-----------------------------------------------------
    if(mysqli_num_rows($sqllmsParticulars) > 0){
        
        while($valuePart = mysqli_fetch_array($sqllmsParticulars)) {
            //-----------------------------------------------------
            $srno++;
            //-----------------------------------------------------
            $sqllmsRoyalty	= $dblms->querylms("SELECT r.id, d.amount
                                        FROM ".CAMPUS_ROYALTY." r
                                        INNER JOIN ".CAMPUS_ROYALTY_DET." d ON d.id_setup = r.id
                                        WHERE r.id_campus = '".$id_campus."'
                                        AND d.id_particular = '".$valuePart['part_id']."' 
                                        AND r.is_deleted != '1'  
                                        ORDER BY d.detail_id DESC LIMIT 1");
            if(mysqli_num_rows($sqllmsRoyalty) > 0){
                $valueRoyalty = mysqli_fetch_array($sqllmsRoyalty);
                $value = $valueRoyalty['amount'];
            }
            else{
                $value = 0;
            }
			//-----------------------------------------------------
            echo'
            <div>
                <div class="col-sm-12">
                    <div class="form-group mb-md">
                        <label class=col-md-9 control-label">'.$valuePart['part_name'].' <span class="required">*</span></label>
                        <input class="form-control" type="hidden" name="id_particular['.$srno.']" id="id_particular" value="'.$valuePart['part_id'].'">
                        <div class="col-md-3">
                            <input class="form-control" type="number" class="form-control" name="amount['.$srno.']" id="amount" value="'.$value.'" required title="Must Be Required"/>
                        </div>
                    </div>
                </div>
            </div>';
        
        }
    }
    else{
        echo'<h5 class="center text text-danger">No Record Found!</h5>';
    }
}
?>