<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";
$std_familyno = cleanvars($_POST['std_familyno']);
if (!empty($std_familyno)) {
    $condition = array(
                         'select'       =>  'f.id, f.status, f.id_type, f.challan_no, f.issue_date, f.due_date, f.paid_date, f.scholarship, f.concession, f.fine, f.total_amount, f.paid_amount, f.remaining_amount, f.note, f.id_session, f.id_month, f.id_campus, s.std_id, s.std_name, s.std_phone, s.std_fathername, c.class_id,c.class_name'
                        ,'join'         => 'INNER JOIN	'.FEES.' AS f ON (s.std_id = f.id_std AND f.id_type IN (1,2) AND f.is_deleted = 0 )
                                            INNER JOIN '.CLASSES.' c ON c.class_id = s.id_class'
                        ,'where'        =>  array(  
                                                     's.is_deleted'     => 0
                                                    ,'s.std_familyno'   => $std_familyno
                                            )
                        ,'search_by'    =>  ' AND f.status IN (2,4)'
                        ,'order_by'     =>  ' f.id ASC'
                        ,'return_type'  =>  'all'
    );
    $STUDENTS = $dblms->getRows(STUDENTS.' AS s', $condition, $sql);
    if ($STUDENTS) {
        echo'
        <style>
            .right {
                text-align: right;
            }
        </style>
        <table class="table table-bordered table-striped table-condensed mb-none">
            <thead>
                <tr>
                    <th width="10" class="center">Sr.</th>
                    <th>Name (Father: '.$STUDENTS[0]['std_fathername'].')</th>
                    <th class="center" width="100">Challan No</th>
                    <th class="center" width="50">Month</th>
                    <th class="center" width="150">Due Date</th>
                    <th class="right" width="100">Payable</th>
                    <th class="right" width="100">If Pay</th>
                </tr>
            </thead>
            <tbody>';
                foreach ($STUDENTS as $keyFee => $valFee) {
                    $due_date    = $valFee['due_date'];
                    $totalAmount = ($valFee['total_amount'] - $valFee['paid_amount']);
                    if($due_date < date('Y-m-d')) {
                        $due_date_after_five_day    = date('Y-m-d', strtotime($due_date. ' + 5 days'));
                        if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 1) {
                            $totalAmount += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
                        } else if ($_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][0] == 2) {
                            if ($due_date_after_five_day > date('Y-m-d')) {
                                $totalAmount += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][1];
                            } else if ($due_date_after_five_day < date('Y-m-d')) {
                                $totalAmount += $_SESSION['userlogininfo']['LOGINCAMPUSLATEFEE'][2];
                            } else {
                                $totalAmount += LATEFEE;	
                            }
                        } else {
                            $totalAmount += LATEFEE;
                        } 
                    }
                    $grandTotal += $totalAmount;
                    echo'
                    <input type="hidden" name="id_fee[]" id="id_fee" value="'.$valFee['id'].'">
                    <input type="hidden" name="challan_no[]" id="challan_no" value="'.$valFee['challan_no'].'">
                    <input type="hidden" name="std_phone[]" id="std_phone" value="'.$valFee['std_phone'].'">
                    <input type="hidden" name="id_std[]" id="id_std" value="'.$valFee['std_id'].'">
                    <input type="hidden" name="id_month[]" id="id_month" value="'.$valFee['id_month'].'">
                    <input type="hidden" name="due_date[]" id="due_date" value="'.$valFee['due_date'].'">
                    <input type="hidden" name="scholarship[]" id="scholarship" value="'.$valFee['scholarship'].'">
                    <input type="hidden" name="concession[]" id="concession" value="'.$valFee['concession'].'">
                    <input type="hidden" name="id_campus[]" value="'.$valFee['id_campus'].'">
                    <tr>
                        <td class="center">'.($keyFee+1).'</td>
                        <td>'.$valFee['std_name'].' ('.$valFee['class_name'].')</td=>
                        <td class="center"><span class="text-danger">'.$valFee['challan_no'].'</span></td>
                        <td class="center">'.substr(get_monthtypes($valFee['id_month']), 0, 3).'</td>
                        <td class="center">'.$valFee['due_date'].'</td>
                        <td class="right">'.number_format($totalAmount).'.00</td>
                        <td class="right">'.number_format($grandTotal).'.00</td>
                    </tr>';
                }
                echo'
            </tbody>
            <tfooter>
                <tr>
                    <th colspan="5" class="right">Grand Total</th>
                    <th class="right">'.number_format($grandTotal).'.00</th>
                    <th class="right"></th>
                    <input type="hidden" id="grandTotal" name="grandTotal" value="'.$grandTotal.'" class="total_amount"/>
                </tr>
            </tfooter>
        </table>
        <div class="form-group">
            <div class="col-md-3">
                <label class="control-label">Received Amount <span class="text-danger">*</span></label>
                <input type="number" id="totaltransamount" name="totaltransamount" value="'.$grandTotal.'" class="form-control totaltransamount paid" required="" />
            </div>
            <div class="col-md-3">
                <label class="control-label" id="label_id_collector">Collector <span class="text-danger">*</span></label>
                <input type="hidden" id="id_collector" name="id_collector" value="'.$_SESSION['userlogininfo']['LOGINIDA'].'" required="" />
                <input type="text" class="form-control" value="'.$_SESSION['userlogininfo']['LOGINNAME'].'" disabled/>
            </div>
            <div class="col-md-3">
                <label class="control-label" id="label_pay_mode">Pay Mode <span class="text-danger">*</span></label>
                <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" required="" id="pay_mode" name="pay_mode">
                    <option value="">Select</option>';
                    foreach($paymethod as $method){
                        echo'<option value="'.$method['id'].'" '.($method['id'] == 1?'selected':'').'>'.$method['name'].'</option>';
                    }
                    echo'
                </select>
            </div>
            <div class="col-md-3">
                <label class="control-label" id="label_paid_date">Paid Date <span class="text-danger">*</span></label>
                <input type="text" id="paid_date" name="paid_date" class="form-control" required="" value="'.date('m/d/Y' , strtotime(date('Y-m-d'))).'" data-plugin-datepicker title="Must Be Required" />
            </div>
        </div>';
    } else {
        echo'no_found';
    }
}
?>