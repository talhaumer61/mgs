<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";
if (!empty($_POST['startDate']) && !empty($_POST['endDate'])) {
    $startDate      = date('Y-m-d' , strtotime(cleanvars($_POST['startDate'])));
    $endDate        = date('Y-m-d' , strtotime(cleanvars($_POST['endDate'])));
    $sql = 'AND (f.due_date BETWEEN \''.$startDate.'\' AND \''.$endDate.'\')';
} else {
    $sql = '';    
}
$sqllms	= $dblms->querylms("SELECT f.id, f.status, f.id_std, f.challan_no, f.issue_date, f.due_date, f.scholarship, f.concession, f.fine, f.total_amount, f.paid_amount, f.remaining_amount, f.note, f.id_session
								FROM ".FEES." f
								WHERE f.is_deleted	= '0'
								AND f.id_type IN (1,2)
								AND f.id_campus		= '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
								AND f.id_std		= '".$_POST['idStd']."'
                                $sql
								ORDER BY f.id DESC");
if (mysqli_num_rows($sqllms)) {
    $srno = 0;
    while($rowsvalues = mysqli_fetch_array($sqllms)){
        $discount = $rowsvalues['scholarship'] + $rowsvalues['concession'];
        $total = $rowsvalues['total_amount'] + $discount - $rowsvalues['fine'];
        $srno++;
        echo '
        <tr>
            <td class="center">'.$srno.'</td>
            <td>'.$rowsvalues['challan_no'].'dsads</td>
            <td>'.$rowsvalues['issue_date'].'</td>
            <td>'.$rowsvalues['due_date'].'</td>
            <td>'.$total.'</td>
            <td>'.$discount.'</td> 
            <td>'.$rowsvalues['fine'].'</td>
            <td>'.$rowsvalues['total_amount'].'</td>
            <td class="center">'.get_payments($rowsvalues['status']).'</td>
        </tr>';
    }
} else {
    echo '
    <tr>
        <th colspan="9" class="text-danger center">No Record Found</th>
    </tr>';
}
?>