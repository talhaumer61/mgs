<?php
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

require_once("include/campus/salary/query_create-salary.php");
include_once("include/header.php");

if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('16', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '16', 'view' => '1'))) {
    echo'
    <title>Salary | '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Employee Salary</h2>
        </header>
        <div class="row">
            <div class="col-md-12">';
                $id_dept    = (!empty($_POST['id_dept']) ? $_POST['id_dept'] : '');
                $id_month   = (!empty($_POST['id_month']) ? $_POST['id_month'] : '');
                $id_campus  = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

                $col        = (!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-4' : 'col-md-6');
                echo'
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-list"></i>  Select Department & Month</h2>
                    </header>
                    <form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="panel-body">
                            <div class="row">';
                                if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
                                    echo'
                                    <div class="'.$col.'">
                                        <div class="form-group mb-md">
                                            <label class="control-label">Sub Campus</label>
                                            <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_dept(this.value)">
                                                <option value="">Select</option>';
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
                                        </div>
                                    </div>';
                                }
                                echo'
                                <div class="'.$col.'">
                                    <div class="form-group">
                                        <label class="control-label">Department <span class="required">*</span></label>
                                        <select data-plugin-selectTwo data-width="100%" id="id_dept" name="id_dept" required title="Must Be Required" class="form-control populate">
                                            <option value="">Select</option>';
                                            $sqllmsdepartment	= $dblms->querylms("SELECT dept_id, dept_name  
                                                                                        FROM ".DEPARTMENTS." 
                                                                                        WHERE dept_status = '1' AND id_campus = '".$id_campus."'  
                                                                                        ORDER BY dept_name ASC");
                                            while($value_dept = mysqli_fetch_array($sqllmsdepartment)) {
                                                echo'<option value="'.$value_dept['dept_id'].'" '.($value_dept['dept_id'] == $id_dept ? 'selected' : '').'>'.$value_dept['dept_name'].'</option>';
                                            }
                                            echo'
                                        </select>
                                    </div>
                                </div>
                                <div class="'.$col.'">
                                    <div class="form-group">
                                        <label class="control-label">Month <span class="required">*</span></label>
                                        <select data-plugin-selectTwo data-width="100%" id="id_month" name="id_month" required title="Must Be Required" class="form-control populate">
                                            <option value="">Select</option>';
                                            foreach($monthtypes as $month) {
                                                echo'<option value="'.$month['id'].'" '.($month['id'] == $id_month ? 'selected' : '').'>'.$month['name'].'</option>';
                                            }
                                            echo'
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <center>
                                <button type="submit" name="view_payslip" id="view_payslip" class="btn btn-primary"><i class="fa fa fa-search"></i> Search</button>
                            </center>
                        </div>
                    </form>
                </section>';

                if(isset($_POST['view_payslip'])){
                    echo'
                    <section class="panel panel-featured panel-featured-primary appear-animation fadeInRight appear-animation-visible" data-appear-animation="fadeInRight" data-appear-animation-delay="100" style="animation-delay: 100ms;">';
                        $sqllmspayslip	= $dblms->querylms("SELECT s.id, s.basic_salary, s.total_allowances, s.total_deductions, s.net_pay, s.dated, d.id_campus,
                                                            e.emply_name, e.emply_phone, e.emply_photo
                                                            FROM ".SALARY." s      
                                                            INNER JOIN ".EMPLOYEES." e ON e.emply_id = s.id_emply
                                                            INNER JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
                                                            WHERE s.id_campus = '".$id_campus."' AND s.month = '".$id_month."'
                                                            AND e.emply_status = '1' AND e.id_dept = '".$id_dept."' ORDER BY id");
                        if(mysqli_num_rows($sqllmspayslip)){
                            echo '
                            <header class="panel-heading">
                                <h2 class="panel-title"><i class="fa fa-list"></i> Generated Payslips</h2>
                            </header>
                            <div class="panel-body">
                                <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
                                    <thead>
                                        <tr role="row">
                                            <th width="40" class="center">Sr.</th>
                                            <th width="40" class="center">Photo</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Basic Salary</th>
                                            <th>Total Allowance</th>
                                            <th>Total Deductions</th>
                                            <th>Net Salary</th>
                                            <th>Creation Date</th>
                                            <th>Payslip </th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                        $srno = 0;
                                        while($value_slip = mysqli_fetch_array($sqllmspayslip)){
                                            $srno++;
                                            if($value_slip['emply_photo']) { 
                                                $photo = '<img src="uploads/images/employees/'.$value_slip['emply_photo'].'" width="40" height="40">' ;
                                            } else {
                                                $photo = '<img src="uploads/defualt.png" width="40" height="40">';
                                            }
                                            echo '                     
                                            <tr role="row" class="odd">
                                                <td class="center">'.$srno.'</td>
                                                <td class="center">'.$photo.'</td>
                                                <td> '.$value_slip['emply_name'].' </td>
                                                <td> '.$value_slip['emply_phone'].' </td>
                                                <td>Rs. '.$value_slip['basic_salary'].' </td>
                                                <td>Rs. '.$value_slip['total_allowances'].' </td>
                                                <td>Rs. '.$value_slip['total_deductions'].' </td>
                                                <td>Rs. '.$value_slip['net_pay'].' </td>
                                                <td> '.date('d M, Y' , strtotime(cleanvars($value_slip['dated']))).' </td>
                                                <td><a href="salary.php?id='.$value_slip['id'].'&id_campus='.$value_slip['id_campus'].'" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Details </a></td>
                                            </tr>';
                                        }
                                        echo '
                                    </tbody>
                                </table>
                            </div>';
                        }else{
                            echo '<h2 class="panel-body mt-none center text-danger">No Record Found!</h2>';
                        }
                        echo'
                    </section>';
                }
                echo'
            </div>
        </div>
    </section>';
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            var datatable = $('#table_export').dataTable({
                bAutoWidth : false,
                ordering: false,
                sDom: "<'text-right mb-md'T>" + $.fn.dataTable.defaults.sDom,
                oTableTools: {
                    sSwfPath: 'assets/vendor/jquery-datatables/extras/TableTools/swf/copy_csv_xls_pdf.swf',
                    aButtons: [
                        {
                            sExtends: 'print',
                            sButtonText: 'Print',
                            sInfo: '',
                            fnClick: function (nButton, oConfig) {
                                datatable.fnSetColumnVis(0, false);
                                datatable.fnSetColumnVis(9, false);
                                
                                this.fnPrint( true, oConfig );
                                
                                window.print();
                                
                                $(window).keyup(function(e) {
                                    if (e.which == 27) {
                                        datatable.fnSetColumnVis(0, true);
                                        datatable.fnSetColumnVis(9, true);
                                    }
                                });
                            }
                        }
                    ]
                }
            });
        });        
		function get_dept(id_campus) {
			$.ajax({  
				type: "POST",  
				url: "include/ajax/get_dept.php",  
				data: "id_campus="+id_campus,  
				success: function(msg){
					$("#id_dept").html(msg);
				}
			});
		}
    </script>
    <?php
}else{
    header("Location: dashboard.php");
}
include_once("include/footer.php");
?>