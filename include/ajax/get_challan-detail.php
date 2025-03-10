<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";
if(isset($_POST['id_std'])){

    $today      = date('m/d/Y');
    $yearmonth  = date('Y-m');

    // EXPLODE ARRAY
    $aray		=	explode('|', $_POST['id_std']);
    $id_std     =	$aray[0];
    $id_class 	=	$aray[1];
    $id_section	=	$aray[2];
    $id_campus	=	$aray[3];
    $is_hostel	=	$aray[4];

    if($is_hostel == '1'){
        $sql = 'AND c.cat_for    IN (1,2)';
    }elseif($is_hostel == '2'){
        $sql = 'AND c.cat_for    IN (1)';
    }

    // SCHOLARSHIP, CONCESSION, FINE
    $sqlScholarship = $dblms->querylms("SELECT ss.id AS id_scholarship,
										SUM(CASE WHEN id_type = '1' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN percent ELSE NULL END) as scholarship_percent,
										SUM(CASE WHEN id_type = '1' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN amount ELSE NULL END) as scholarship_amount,
										SUM(CASE WHEN id_type = '1' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN id_feecat ELSE NULL END) as scholarship_feecat,
										SUM(CASE WHEN id_type = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN percent ELSE NULL END) as concession_percent,
										SUM(CASE WHEN id_type = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN amount ELSE NULL END) as concession_amount,
										SUM(CASE WHEN id_type = '2' AND start_date < '".date('Y-m-d')."' AND end_date > '".date('Y-m-d')."' THEN id_feecat ELSE NULL END) as concession_feecat,
                                        SUM(CASE WHEN id_type = '3' AND yearmonth = '".$yearmonth."' THEN amount ELSE NULL END) as fine_amount,
                                        SUM(CASE WHEN id_type = '3' AND yearmonth = '".$yearmonth."' THEN id_feecat ELSE NULL END) as fine_feecat
										FROM ".SCHOLARSHIP." ss
										WHERE id_campus	= '".cleanvars($id_campus)."' 
                                        AND id_session  = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
										AND status		= '1' 
										AND is_deleted	= '0'
										AND id_std		= '".cleanvars($id_std)."'
									");
    $valScholarship     = mysqli_fetch_array($sqlScholarship);
    $id_scholarship     = $valScholarship['scholarship_feecat'];
    $id_feehead         = $valScholarship['concession_feecat'];

    // CHECK IF IT'S FIRST CHALLAN OR NOT
    $sqlCheck = $dblms->querylms("SELECT id, remaining_amount
                                    FROM ".FEES."
                                    WHERE is_deleted    = '0'
                                    AND id_std  	    = '".cleanvars($id_std)."'
                                    AND id_class		= '".cleanvars($id_class)."'
                                    AND id_section	    = '".cleanvars($id_section)."'
                                    AND id_campus	    = '".cleanvars($id_campus)."'
                                    AND id_session	    = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                    ORDER BY id DESC LIMIT 1
                                ");
    if(mysqli_num_rows($sqlCheck) == 1){        
        $valCheck = mysqli_fetch_array($sqlCheck);
        $rem_amount = $valCheck['remaining_amount'];
        // $duration = "";
    }else{
        $rem_amount = 0;
        // $duration = ", 'Yearly'";
    }

    // GET FEE SETUP AND AMOUNTS
    $sqlFees = $dblms->querylms("SELECT fs.id, d.id, d.id_setup, d.id_cat, d.amount, d.duration, c.cat_id, c.cat_name
                                    FROM ".FEESETUP." fs
                                    INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = fs.id
                                    INNER JOIN ".FEE_CATEGORY." c ON c.cat_id = d.id_cat
                                    WHERE fs.is_deleted	= '0'
                                    AND fs.status		= '1'
                                    AND fs.id_class		= '".cleanvars($id_class)."'
                                    AND fs.id_section	= '".cleanvars($id_section)."'
                                    AND fs.id_campus	= '".cleanvars($id_campus)."'
                                    AND fs.id_session	= '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                    $sql
                                    ORDER BY c.cat_id ASC");
    if(mysqli_num_rows($sqlFees)>0){
        $srno = 0;
        $amount = 0;
        $total_amount = 0;

        echo'<div class="form-group">';
        while($valFees = mysqli_fetch_array($sqlFees)){
            $srno++;

            // SCHOLARSHIP
            if($valScholarship['scholarship_feecat'] != '0' && $valScholarship['scholarship_feecat'] == $valFees['cat_id']){
                if($valScholarship['scholarship_percent'] != '0'){
                    $scholarship = ($valFees['amount'] * $valScholarship['scholarship_percent']) / 100;
                }
                elseif($valScholarship['scholarship_amount'] != '0'){
                    $scholarship = $valScholarship['scholarship_amount'];
                }
            }
            // CONCESSION
            if(!empty($valScholarship['concession_feecat']) && $valScholarship['concession_feecat'] != '0' && $valScholarship['concession_feecat'] == $valFees['cat_id']){
                if($valScholarship['concession_percent'] != '0'){
                    $concession = ($valFees['amount'] * $valScholarship['concession_percent']) / 100;
                }
                elseif($valScholarship['concession_concession'] != '0'){
                    $concession = $valScholarship['concession_amount'];
                }
            }            
            // FINE
            if(!empty($valScholarship['fine_feecat']) && $valScholarship['fine_feecat'] != '0' && $valScholarship['fine_feecat'] == $valFees['cat_id']){
                if($valScholarship['fine_percent'] != '0'){
                    $fine = $valScholarship['fine_amount'];
                }
            }
            echo '
            <div class="col-md-3">
                <input class="form-control" type="hidden" name="id_cat['.$srno.']" id="id_cat['.$srno.']" value="'.$valFees['cat_id'].'">
                <label class="control-label">'.$valFees['cat_name'].' <span class="required">*</span></label>
                <input class="form-control sum" type="number" class="form-control" name="amount['.$srno.']" id="amount['.$srno.']" value="'.$valFees['amount'].'" required title="Must Be Required"/>
            </div>';
            $amount = $valFees['amount'];
            $total_amount = $total_amount + $amount;
        }

        // SCHOLARSHIP
        if($valScholarship['scholarship_feecat'] == '0'){
            if(!empty($valScholarship['scholarship_percent'])){
                $scholarship = ($total_amount * $valScholarship['scholarship_percent']) / 100;
            }
            elseif(!empty($valScholarship['scholarship_amount'])){
                $scholarship = $valScholarship['scholarship_amount'];
            }
        }
        // CONCESSION
        if($valScholarship['concession_feecat'] == '0'){
            if(!empty($valScholarship['concession_percent'])){
                $concession = ($total_amount * $valScholarship['concession_percent']) / 100;
            }
            elseif(!empty($valScholarship['concession_amount'])){
                $concession = $valScholarship['concession_amount'];
            }
        }        
        // FINE
        if($valScholarship['fine_feecat'] == '0'){
            if(!empty($valScholarship['fine_amount'])){
                $fine = $valScholarship['fine_amount'];
            }
        }

        // REMAINING AMOUNT, SCHOLARSHIP, CONCESSION, FINE
        if($rem_amount != 0){
            echo'
            <div class="col-md-3">
                <label class="control-label">Remaining Amount <span class="required">*</span></label>
                <input class="form-control sum" type="number" class="form-control" name="remaining_amount" id="remaining_amount" value="'.$rem_amount.'" required title="Must Be Required"/>
            </div>';
            $total_amount = $total_amount + $rem_amount;
        }
        if(!empty($fine)){
            echo'
            <div class="col-md-3">
                <label class="control-label">Fine <span class="required">*</span></label>
                <input class="form-control sum" type="number" class="form-control" name="fine" id="fine" value="'.$fine.'" required title="Must Be Required"/>
            </div>';
            $total_amount = $total_amount + $fine;
        }
        if(!empty($scholarship)){
            echo'
            <div class="col-md-3">
                <label class="control-label">Scholarship <span class="required">*</span></label>
                <input class="form-control sub" type="text" class="form-control" name="scholarship" id="scholarship" value="'.$scholarship.'" required title="Must Be Required"/>
                <input type="hidden" name="id_scholarship" value="'.$id_scholarship.'" />
            </div>';
            $total_amount = $total_amount - $scholarship;
        }
        if(!empty($concession)){
            echo'
            <div class="col-md-3">
                <label class="control-label">Concession <span class="required">*</span></label>
                <input class="form-control sub" type="number" class="form-control" name="concession" id="concession" value="'.$concession.'" required title="Must Be Required"/>
                <input type="hidden" name="id_feehead" value="'.$id_feehead.'" />
            </div>';
            $total_amount = $total_amount - $concession;
        }
        echo'
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <label class="control-label">Total Payable <span class="required">*</span></label>
                <input class="form-control total_amount" type="text" class="form-control" name="total_amount" id="total_amount" value="'.$total_amount.'" required title="Must Be Required" readonly/>
            </div>
        </div>';
    }else{
        echo'<p class="text text-danger center">No Fee Added! <br> Firstly Kindly Add Fee Details</p>';
    }
}else{
    echo'no result';
}
?>
<script type="text/javascript">
    $(document).on("keyup", ".sum", function() {
        var sum = 0;
        $(".sum").each(function(){
            sum += +$(this).val();
        });

        var sub = 0;
        $(".sub").each(function(){
            sub += +$(this).val();
        });

        var total_amount = sum - sub
        $(".total_amount").val(total_amount);
    });
    $(document).on("keyup", ".sub", function() {
        var sum = 0;
        $(".sum").each(function(){
            sum += +$(this).val();
        });
        
        var sub = 0;
        $(".sub").each(function(){
            sub += +$(this).val();
        });

        var total_amount = sum - sub
        $(".total_amount").val(total_amount);
    });
</script>