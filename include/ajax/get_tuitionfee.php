<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

// TUITION AMOUNT
if($_POST['camp'] && $_POST['cls'] && $_POST['id_cat']){
    if($_POST['id_cat'] == 'all'){
        $sql = "";
    }else{
        $sql = "AND d.id_cat = '".$_POST['id_cat']."'";
    }
    $sqllmsfeesetup	= $dblms->querylms("SELECT f.id, SUM(d.amount) as amount
                                            FROM ".FEESETUP." f
                                            INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = f.id	
                                            WHERE f.status      = '1'
                                            AND f.is_deleted    = '0'
                                            AND f.id_session    = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                            AND f.id_campus     = '".$_POST['camp']."'
                                            AND f.id_class      = '".$_POST['cls']."'
                                            $sql LIMIT 1");
    if(mysqli_num_rows($sqllmsfeesetup) > 0){
        $valTuitionFee = mysqli_fetch_array($sqllmsfeesetup);
        $amount = ($valTuitionFee['amount'] * $_POST['percentage']) / 100;
        echo'<input type="hidden" class="form-control amount" name="amount['.$_POST['srno'].']['.$_POST['stdsrno'].']" id="amount'.$_POST['srno'].''.$_POST['stdsrno'].'" placeholder="Amount" value="'.$amount.'" readonly/>';
    }else{
        echo'<input type="hidden" class="form-control amount" name="amount['.$_POST['srno'].']['.$_POST['stdsrno'].']" id="amount'.$_POST['srno'].''.$_POST['stdsrno'].'" placeholder="Amount" readonly/>';
    }
}

// AMOUNT FOR FEE HEAD
elseif(isset($_POST['id_feecat']) && isset($_POST['id_class'])){
    if($_POST['id_feecat'] == '0'){
        $sql = "";
    }else{
        $sql = "AND d.id_cat = '".$_POST['id_feecat']."'";
    }
    $sqllmsfeesetup	= $dblms->querylms("SELECT f.id, SUM(d.amount) as amount
                                            FROM ".FEESETUP." f
                                            INNER JOIN ".FEESETUPDETAIL." d ON d.id_setup = f.id	
                                            WHERE f.status      = '1'
                                            AND f.is_deleted    = '0'
                                            AND f.id_session    = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                            AND f.id_campus     = '".cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS'])."'
                                            AND f.id_class      = '".cleanvars($_POST['id_class'])."'
                                            $sql LIMIT 1");
    if(mysqli_num_rows($sqllmsfeesetup) > 0){
        $valTuitionFee = mysqli_fetch_array($sqllmsfeesetup);
        echo'    
        <div class="form-group">
            <label class="col-md-3 control-label">Fee Amount </label>
            <div class="col-md-9">
                <input type="text" class="form-control" value="'.$valTuitionFee['amount'].'" title="Must Be Required" readonly/>
            </div>
        </div>';
    }
}
?>