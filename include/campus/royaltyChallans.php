<style>
    .card{
        padding: 20px 20px 10px 20px;
        font-size: 30px;
        border-radius:10px;
        margin-left: 4%;
        margin-right: 4%;
        }
    .val{
        font-size: 20px;
        margin-left: 18%;
        }
    .count{
        font-size: 14px;
        margin-right: 18%;
        }
    .span{
        font-size:14px;
        }
</style>
<?php 
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('71', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))) {
echo '
<title> Royalty Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Royalty Panel </h2>
	</header>
    <div class="row">
        <div class="col-md-12">';
        
        // Vars
        $sql2 = "";
        $search_word = "";

        // FIlters
        if(isset($_GET['show']))
        {
            //  word
            if(isset($_GET['search_word']))
            {
                $search_word = $_GET['search_word'];
                $sql2 = "AND (r.challan_no LIKE '%".$_GET['search_word']."%' OR r.total_amount LIKE '%".$_GET['search_word']."%' OR c.campus_name LIKE '%".$_GET['search_word']."%')";
            }
        }
        $id_campus 		= ((isset($_GET['id_campus']) && !empty($_GET['id_campus'])))? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
        //---------------- Cards Query -----------------------
        $sqllmspaid	= $dblms->querylms("SELECT COUNT(r.id) as count_paid, SUM(r.paid_amount) as paid
                                        FROM ".FEES." r				   						 
                                        INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
                                        WHERE r.status = '1' AND r.id_type = '3' AND r.is_deleted != '1' AND r.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' $sql2");
        $value_paid = mysqli_fetch_array($sqllmspaid);
        if($value_paid['paid']){$paid = $value_paid['paid'];}else{$paid = 0;}
        //------------------------------------------------------
        $sqllmspending	= $dblms->querylms("SELECT COUNT(r.id) as count_pending, SUM(r.total_amount) as pending
                                        FROM ".FEES." r				   						 
                                        INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
                                        WHERE r.status = '2' AND r.id_type = '3' AND r.is_deleted != '1' AND r.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' $sql2");
        $value_pending = mysqli_fetch_array($sqllmspending);
        if($value_pending['pending']){$pending = $value_pending['pending'];}else{$pending = 0;}
        //------------------------------------------------------
        $sqllmspartpaid	= $dblms->querylms("SELECT COUNT(r.id) as count_partpaid, SUM(r.total_amount) as partpaid
                                        FROM ".FEES." r				   						 
                                        INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
                                        WHERE r.status = '4' AND r.id_type = '3' AND r.is_deleted != '1' AND r.id_campus = '".cleanvars($_SESSION['userlogininfo']['LOGINIDA'])."' $sql2");
        $value_partpaid = mysqli_fetch_array($sqllmspartpaid);
        if($value_partpaid['partpaid']){$partpaid = $value_partpaid['partpaid'];}else{$partpaid = 0;}
        //--------------------- Cards ---------------------------
        echo '
        <div class="row mt-none mb-md">
            <div class="col-sm-12 col-md-12 col-lg-3 bg bg-success card mb-sm">
                <i class="fa fa-star" aria-hidden="true"></i> Total Paid
                <p class="val mt-md"><span class="span">Rs:</span> '.number_format($paid).'</p>
                <p class="count pull-right"><span class="span">Challan:</span> '.$value_paid['count_paid'].'</p>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-3 bg bg-warning card mb-sm">
                <i class="fa fa-refresh" aria-hidden="true"></i> Total Pending
                <p class="val mt-md"><span class="span">Rs:</span> '.number_format($pending).'</p>
                <p class="count pull-right"><span class="span">Challan:</span> '.$value_pending['count_pending'].'</p>
            </div>
            <div class="col-sm-12 col-md-12 col-lg-3 bg bg-info card mb-sm">
                <i class="fa fa-ban" aria-hidden="true"></i> Partial Paid
                <p class="val mt-md"><span class="span">Rs:</span> '.number_format($partpaid).'</p>
                <p class="count pull-right"><span class="span">Challan:</span> '.$value_partpaid['count_partpaid'].'</p>
            </div>
        </div>
        <section class="panel panel-featured panel-featured-primary">
            <header class="panel-heading">';
                // if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){ 
                // 	echo'<a href="#print_challan" class="modal-with-move-anim ml-sm btn btn-primary btn-xs pull-right"><i class="fa fa-print"></i> Print Challan</a>';
                // }
                echo'
                <h2 class="panel-title"><i class="fa fa-list"></i>  Royalty Challans List</h2>
            </header>
            <div class="panel-body">
                <form action="#" method="GET" autocomplete="off">
                    <div class="row form-group mb-md">';
                        if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
                            echo'
                            <div class="col-md-3">
                                <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus"> 
                                    <option value="">Select Sub Campus</option>';
                                    $sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
                                                                    FROM ".CAMPUS." 
                                                                    WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
                                                                    AND campus_status	= '1'
                                                                    AND is_deleted		= '0'
                                                                    ORDER BY campus_id ASC");
                                    while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
                                        echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $id_campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
                                    }
                                    echo'
                                </select>
                            </div>';
                        endif;
                        echo'
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <div class="input-group mb-lg">
                                <input type="search" name="search_word" id="search_word" class="form-control" value="'.$search_word.'" placeholder="Search">
                                <div class="input-group-addon" style="padding: 0 !important; border: 0 !important;">
                                    <button type="submit" name="show" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>';
                $sql = "SELECT r.id, r.status, r.id_month, r.challan_no, r.issue_date, r.due_date, r.paid_date, r.total_amount, r.paid_amount, r.remaining_amount, s.session_name, c.campus_name, c.id_zone
                        FROM ".FEES." r				   						 
                        INNER JOIN ".SESSIONS." s ON s.session_id = r.id_session		 	
                        INNER JOIN ".CAMPUS." c ON c.campus_id = r.id_campus 
                        WHERE r.is_deleted != '1' AND r.id_type = '3' $sql2
                        AND r.id_campus = '".$id_campus."'
                        ORDER BY r.id DESC";

                $sqllms	= $dblms->querylms($sql);
                $count = mysqli_num_rows($sqllms);

                if($page == 0) { $page = 1; }			//if no page var is given, default to 1.
                $prev		= $page - 1;				//previous page is page - 1
                $next		= $page + 1;				//next page is page + 1
                $lastpage	= ceil($count/$Limit);		//lastpage is = total pages / items per page, rounded up.
                $lpm1		= $lastpage - 1;

                $sqllms	= $dblms->querylms("$sql LIMIT ".($page-1)*$Limit .",$Limit");

                if(mysqli_num_rows($sqllms) > 0){
                    echo'
                    <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
                        <thead>
                            <tr>
                                <th class="center" width="40">Sr.</th>
                                <th>Challan No</th>
                                <th>Month</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Session</th>
                                <th>Campus</th>
                                <th>Payable</th>
                                <th>Balance</th>
                                <th width="100" class="center">Status</th>
                                <th width="100" class="center">Options</th>
                            </tr>
                        </thead>
                        <tbody>';
                            if($page == 1){
                                $srno = 0;
                            }else{
                                $srno = ($page - 1) * $Limit;
                            }
                            while($rowsvalues = mysqli_fetch_array($sqllms)) {
                                $srno++;
                                echo '
                                <tr>
                                    <td class="center">'.$srno.'</td>
                                    <td>'.$rowsvalues['challan_no'].'</td>
                                    <td>'.get_monthtypes($rowsvalues['id_month']).'</td>
                                    <td>'.$rowsvalues['issue_date'].'</td>
                                    <td>'.$rowsvalues['due_date'].'</td>
                                    <td>'.$rowsvalues['session_name'].'</td>
                                    <td>'.$rowsvalues['campus_name'].'</td>
                                    <td>'.number_format(round($rowsvalues['total_amount'])).'</td>
                                    <td>'.number_format(round($rowsvalues['total_amount'] - $rowsvalues['paid_amount'])).'</td>
                                    <td class="center">'.get_payments($rowsvalues['status']).'</td>
                                    <td class="center">';
                                    echo '
                                        <a class="btn btn-success btn-xs" class="center" href="royaltyChallanPrint.php?id='.$rowsvalues['challan_no'].'" target="_blank"> <i class="fa fa-file" data-toggle="tooltip" title="Print Challan"></i></a>
                                    </td>
                                </tr>';
                            }
                            echo '
                        </tbody>
                    </table>';
                    include_once('include/pagination.php');
                }
                else{
                    echo'<div class="panel-body"><h2 class="text text-center text-danger mt-lg">No Record Found!</h2></div>';
                }
                echo'
            </div>
        </section>
        </div>
    </div>
</section>';
}else{
	header("location: dashboard.php");
}
?>