<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '29', 'view' => '1')) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '27', 'view' => '1'))){
//-----------------------------------------------
if(isset($_POST['campus']) && ($_POST['campus'] > 0)){
    $campus = $_POST['campus'];
    $sql2 = "AND t.id_campus = '".$_POST['campus']."'";
}else{
    $campus = "";
    $sql2 = "";
}
//-----------------------------------------------
if(isset($_POST['view_report'])){
    $year = $_POST['year'];	
}	
//-----------------------------------------------
$current_year = date('Y');		
//-----------------------------------------------	
echo'
<style>
.ui-datepicker-calendar {
    display: none;
 }
</style>
<title>Income & Expense Report | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Income Expense Report</h2>
	</header>
    <!-- INCLUDEING PAGE -->
    <div class="row">
        <div class="col-md-12">

            <section class="panel panel-featured panel-featured-primary">
                <header class="panel-heading">
                    <h2 class="panel-title"><i class="fa fa-list"></i>  Select Campus</h2>
                </header>
                <form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                    <div class="panel-body">
                        <div class="row mb-lg">
                            <div class="col-md-offset-4 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Campus <span class="required">*</span></label>
                                    <select data-plugin-selectTwo data-width="100%" name="campus" id="campus" required title="Must Be Required" class="form-control populate">
                                        <option value="">Select </option>
                                        <option value="0"';if($campus == 0){echo'selected';} echo'>All Campuses</option>';
                                        $sqllmscampus	= $dblms->querylms("SELECT c.campus_id, c.campus_name
                                                                                FROM ".CAMPUS." c  
                                                                                WHERE c.campus_id != '' AND campus_status = '1'
                                                                                ORDER BY c.campus_name ASC");
                                        while($value_campus = mysqli_fetch_array($sqllmscampus)){
                                            if($value_campus['campus_id'] == $campus){
                                                echo'<option value="'.$value_campus['campus_id'].'" selected>'.$value_campus['campus_name'].'</option>';
                                            }
                                            else{
                                                echo'<option value="'.$value_campus['campus_id'].'">'.$value_campus['campus_name'].'</option>';
                                            }
                                        }
                                        echo'
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-offset-4 col-md-4 mt-md">
                                <div class="form-group">
                                    <label class="control-label">Year <span class="required">*</span></label>
                                    <input type="text" class="date-own form-control pickayear" name="year" id="year" value="'.$year.'" max="'.$current_year.'">
                                </div>
                            </div>
                        </div>
                        <center>
                            <button type="submit" name="view_report" id="view_report" class="btn btn-primary"><i class="fa fa-search"></i> Show Result</button>
                        </center>
                    </div>
                </form>
            </section>';
            //-----------------------------------------------
            if(isset($_POST['view_report'])){
                echo '
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-list"></i>  Income Expense Report of <b>'.$year.'</b></h2>
                    </header>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
                            <thead>
                                <tr>
                                    <th class="text-center">Sr.</th>
                                    <th>Particular</th>
                                    <th>Income</th>
                                    <th>Expense</th>
                                    <th width="100">Profit / Loss</th>
                                </tr>
                            </thead>
                            <tbody>';
                                //-----------------------------------------------------
                                $srno = 0;
                                $t_inc = 0;
                                $t_exp = 0;
                                $prof_loss = 0;
                                $net_profloss = 0;
                                //-----------------------------------------------------
                                foreach($monthtypes as $month) {

                                    $income = 0;
                                    $expense = 0;
                                    //---------------------------Income Start--------------------------
                                    $sqllms_inc	= $dblms->querylms("SELECT t.trans_title, t.trans_amount, h.head_name
                                                                    FROM ".ACCOUNT_TRANS." t
                                                                    INNER JOIN ".ACCOUNT_HEADS." h ON h.head_id = t.id_head
                                                                    WHERE t.trans_status= '1' AND t.trans_type = '1' $sql2
                                                                    AND t.dated LIKE '".$year."-%".$month['id']."-%'
                                                                    ORDER BY t.trans_id DESC");
                                    if(mysqli_num_rows($sqllms_inc) > 0) {
                                        $value_inc = mysqli_fetch_array($sqllms_inc);
                                        $income = $value_inc['trans_amount'];
                                    }
                                    //----------------------------Expense Start----------------------
                                    $sqllms_exp	= $dblms->querylms("SELECT t.trans_id, t.trans_title, t.trans_amount, t.voucher_no, t.trans_method, t.dated, h.head_name
                                                                    FROM ".ACCOUNT_TRANS." t
                                                                    INNER JOIN ".ACCOUNT_HEADS." h ON h.head_id = t.id_head
                                                                    WHERE t.trans_status= '1' AND t.trans_type = '2' $sql2
                                                                    AND t.dated LIKE '".$year."-%".$month['id']."-%'
                                                                    ORDER BY t.trans_id DESC");
                                    if(mysqli_num_rows($sqllms_exp) > 0) {
                                        $value_exp = mysqli_fetch_array($sqllms_exp);
                                        $expense = $value_exp['trans_amount'];
                                    }
                                    //----------------------------Expense end------------------------
                                    $prof_loss = $income - $expense;
                                    //-----------------------------------------------------
                                    $srno++;
                                    //-----------------------------------------------------
                                    echo '
                                    <tr>
                                        <td style="text-align:center;">'.$srno.'</td>
                                        <td>Profit / Lost for the month of '.$month['name'].' '.$year.'</td>
                                        <td>'.$income.'</td>
                                        <td>'.$expense.'</td>
                                        <td class="text-center">';
                                            if($income > $expense){
                                                echo'<p class="btn btn-success btn-xs">'.$prof_loss.'</p>';
                                            }elseif($income < $expense){
                                                echo'<p class="btn btn-danger btn-xs">'.$prof_loss.'</p>';
                                            } else{
                                                echo'<p class="btn btn-info btn-xs">'.$prof_loss.'</p>';
                                            }
                                        echo'
                                        </td>
                                    </tr>';
                                    $t_inc = $t_inc + $income;
                                    $t_exp = $t_exp + $expense;
                                    $net_profloss = $net_profloss + $prof_loss;
                                }
                                echo '
                                <tr>
                                    <th colspan="4" class="text-center">Net Profit / Loss</th>
                                    <td class="text-center">';
                                    if($t_inc > $t_exp){
                                        echo'<p class="pull-left label label-success"><b style="font-size: 10px;">Profit: <span style="font-size: 15px;">'.number_format( $net_profloss).'</span> Rs</b></p>';
                                    }elseif($t_inc < $t_exp){
                                        echo' <p class=" pull-left label label-danger"><b style="font-size: 10px;">Loss: <span style="font-size: 15px;">'.number_format( $net_profloss).'</span> Rs</b></p>';
                                    }
                                    else{
                                        echo'<p class="pull-left label label-info"><b style="font-size: 10px;"><span style="font-size: 15px;">'.number_format( $net_profloss).'</span> Rs</b></p>';
                                    }
                                echo'</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </section>';
            }
            echo'
        </div>
    </div>
</section>';
//-----------------------------------------------
} else {
	header("Location: dashboard.php");
}

?>
<script>
        //USED BY: All date picking forms
        $(document).ready(function(){
            $(".pickayear").datepicker({
            format: "yyyy",
            language: "lang",
            viewMode: "years", 
            minViewMode: "years",
            autoclose: true
            });	
        });
    </script>