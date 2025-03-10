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
                if(isset($_GET['id'])){
                    $id_campus  = (!empty($_GET['id_campus']) ? $_GET['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

                    $sqllmspayslip	= $dblms->querylms("SELECT s.id, s.slip_no, s.month, s.basic_salary, s.total_allowances, s.total_deductions, s.net_pay, s.dated,
                                                        e.emply_name, e.emply_joindate, e.emply_phone, e.emply_email, d.dept_name, dp.designation_name, c.campus_name, c.campus_address, c.campus_email, c.campus_phone, c.campus_logo
                                                        FROM ".SALARY." s
                                                        INNER JOIN ".EMPLOYEES." e ON e.emply_id = s.id_emply
                                                        LEFT JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
                                                        LEFT JOIN ".DESIGNATIONS." dp ON dp.designation_id = e.id_designation
                                                        INNER JOIN ".CAMPUS." c ON c.campus_id = s.id_campus
                                                        WHERE s.id_campus = '".$id_campus."'
                                                        AND s.status = '1' AND id = '".$_GET['id']."' LIMIT 1");
                    $value_pay = mysqli_fetch_array($sqllmspayslip);
                    echo '
                    <section class="panel">
                        <div class="panel-body" id="invoice_print">
                            <div class="invoice">
                                <header class="clearfix">
                                    <div class="row">
                                        <div class="col-sm-4 mt-md">
                                            <h2 class="h2 mt-none mb-sm text-dark text-weight-bold">PAYSLIP</h2>
                                            <h4 class="h4 m-none text-dark text-weight-bold">#'.$value_pay['slip_no'].'</h4>
                                        </div>
                                        <div class="col-sm-8 text-right mt-md mb-md">
                                            <address class="ib mr-xlg">
                                                <span class="text-dark"><b>'.$value_pay['campus_name'].'</b></span><br>
                                                '.$value_pay['campus_address'].'<br> 
                                                '.$value_pay['campus_phone'].'<br>  
                                                '.$value_pay['campus_email'].'  
                                            </address>
                                            <div class="ib">
                                                <img src="uploads/images/campus/'.$value_pay['campus_logo'].'" width="70" alt="'.TITLE_HEADER.'">
                                            </div>
                                        </div>
                                    </div>
                                </header>                                
                                <div class="bill-info">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="bill-to">
                                                <p class="h5 mb-xs text-dark text-weight-semibold">To:</p>
                                                <address>
                                                    '.$value_pay['emply_name'].'<br>
                                                    Designation : '.$value_pay['designation_name'].'<br>
                                                    Department : '.$value_pay['dept_name'].'<br>
                                                    Joining Date : '.date('d M, Y' , strtotime(cleanvars($value_pay['emply_joindate']))).'<br>
                                                    Phone : '.$value_pay['emply_phone'].'<br>
                                                    Email : '.$value_pay['emply_email'].'
                                                </address>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="bill-data text-right">
                                                <p class="mb-none">
                                                    <span class="text-dark">Creation Date : </span>
                                                    <span>'.date('d M, Y' , strtotime(cleanvars($value_pay['dated']))).'</span>
                                                </p>
                                                <p class="mb-none">
                                                    <span class="text-dark">Salary Month : </span>
                                                    <span>'.get_monthtypes($value_pay['month']).'</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <section class="panel" style="box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);">
                                            <header class="panel-heading" style="border-bottom: 2px solid #0088cc;">
                                                <h2 class="panel-title">Allowances</h2>
                                            </header>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table invoice-items">
                                                        <thead>
                                                            <tr class="h5 text-dark">
                                                                <th id="cell-id" class="text-weight-semibold">Sr.</th>
                                                                <th id="cell-item" class="text-weight-semibold">Name</th>
                                                                <th id="cell-desc" class="text-weight-semibold">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';
                                                            $sqllmsallowance	= $dblms->querylms("SELECT name, amount
                                                                                                    FROM ".SALARY_PART."
                                                                                                    WHERE type = '1' AND id_voucher = '".$value_pay['id']."' ORDER BY id ASC");
                                                            $srno = 0;
                                                            while($value_allow = mysqli_fetch_array($sqllmsallowance)) {
                                                                $srno++;
                                                                echo'
                                                                <tr>
                                                                    <td>'.$srno.'</td>
                                                                    <td class="text-weight-semibold text-dark">'.$value_allow['name'].'</td>
                                                                    <td>'.$value_allow['amount'].'</td>
                                                                </tr>';
                                                            }
                                                            echo '
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div class="col-md-6">
                                        <section class="panel" style="box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);">
                                            <header class="panel-heading" style="border-bottom: 2px solid #0088cc;">
                                                <h2 class="panel-title">Deductions</h2>
                                            </header>
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    <table class="table invoice-items">
                                                        <thead>
                                                            <tr class="h5 text-dark">
                                                                <th id="cell-id" class="text-weight-semibold">Sr.</th>
                                                                <th id="cell-item" class="text-weight-semibold">Name</th>
                                                                <th id="cell-desc" class="text-weight-semibold">Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>';
                                                            $sqllmsdeductions	= $dblms->querylms("SELECT name, amount
                                                                                                    FROM ".SALARY_PART."
                                                                                                    WHERE type = '2' AND id_voucher = '".$value_pay['id']."' ORDER BY id ASC");
                                                            $srno = 0;
                                                            while($value_ded = mysqli_fetch_array($sqllmsdeductions)) {
                                                                $srno++;
                                                                echo'
                                                                <tr>
                                                                    <td>'.$srno.'</td>
                                                                    <td class="text-weight-semibold text-dark">'.$value_ded['name'].'</td>
                                                                    <td>'.$value_ded['amount'].'</td>
                                                                </tr>';
                                                            }
                                                            echo'
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                                
                                <div class="invoice-summary">
                                    <div class="row">
                                        <div class="col-sm-4 col-sm-offset-8">
                                            <table class="table h5 text-dark">
                                                <tbody>
                                                    <tr class="b-top-none">
                                                        <td colspan="2">Basic Salary</td>
                                                        <td class="text-left">Rs. '.$value_pay['basic_salary'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">Total Allowance</td>
                                                        <td class="text-left">Rs. '.$value_pay['total_allowances'].'</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">Total Deductions</td>
                                                        <td class="text-left">Rs. '.$value_pay['total_deductions'].'</td>
                                                    </tr>
                                                    <tr class="h4">
                                                        <td colspan="2">Net Salary</td>
                                                        <td class="text-left">Rs. '.$value_pay['net_pay'].'</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <footer class="panel-footer">
                            <div class="text-right mr-lg">
                                <a href="#" onclick="PrintElem(\'#invoice_print\')" class="btn btn-primary ml-sm"><i class="glyphicon glyphicon-print"></i></a>
                            </div>
                        </footer>
                    </section>';
                }

                if(!isset($_GET['id'])){
                    $id_dept    = (!empty($_POST['id_dept']) ? $_POST['id_dept'] : '');
                    $id_emply   = (!empty($_POST['id_emply']) ? $_POST['id_emply'] : '');
                    $id_month   = (!empty($_POST['id_month']) ? $_POST['id_month'] : '');
                    $id_campus  = (!empty($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);

                    $col        = (!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-3' : 'col-md-4');
                    echo'
                    <section class="panel panel-featured panel-featured-primary">
                        <header class="panel-heading">
                            <h2 class="panel-title"><i class="fa fa-list"></i>  Select Department</h2>
                        </header>
                        <form action="#" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                            <div class="panel-body">
                                <div class="row mb-lg">';
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
                                    } else {
                                        echo '
                                        <input type="hidden" name="id_campus" value="'.$id_campus.'">';
                                    }
                                    echo'
                                    <div class="'.$col.'">
                                        <div class="form-group">
                                            <label class="control-label">Department <span class="required">*</span></label>
                                            <select data-plugin-selectTwo data-width="100%" id="id_dept" name="id_dept" onchange="get_deptemployee(this.value)" required title="Must Be Required" class="form-control populate">
                                                <option value="">Select</option>';
                                                $sqllmsdepartment	= $dblms->querylms("SELECT DISTINCT d.dept_id, d.dept_name  
                                                                                            FROM ".DEPARTMENTS." AS d
                                                                                            INNER JOIN ".EMPLOYEES." AS e ON e.id_dept = d.dept_id
                                                                                            WHERE d.dept_status = '1' AND d.id_campus = '".$id_campus."'  
                                                                                            ORDER BY d.dept_name ASC");
                                                while($value_dept = mysqli_fetch_array($sqllmsdepartment)) {
                                                    echo'<option value="'.$value_dept['dept_id'].'" '.($value_dept['dept_id'] == $id_dept ? 'selected' : '').'>'.$value_dept['dept_name'].'</option>';
                                                }
                                                echo'
                                            </select>
                                        </div>
                                    </div>
                                    <div class="'.$col.'">
                                        <div class="form-group">
                                            <label class="control-label">Employee <span class="required">*</span></label>
                                            <select class="form-control populate" data-plugin-selectTwo data-width="100%" id="id_emply" name="id_emply" required title="Must Be Required">
                                                <option value="">Select</option>';
                                                $sqlEmply	= $dblms->querylms("SELECT emply_id, emply_name 
                                                                                    FROM ".EMPLOYEES."
                                                                                    WHERE id_campus     = '".cleanvars($id_campus)."'
                                                                                    AND id_dept         = '".cleanvars($id_dept)."'
                                                                                    AND emply_status    = '1'
                                                                                    AND is_deleted      = '0'
                                                                                ");
                                                while($valEmply = mysqli_fetch_array($sqlEmply)) {
                                                    echo'<option value="'.$valEmply['emply_id'].'" '.($valEmply['emply_id'] == $id_emply ? 'selected' : '').'>'.$valEmply['emply_name'].'</option>';
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
                                    <button type="submit" name="view_employee" id="view_employee" class="btn btn-primary"><i class="fa fa-check-square-o"></i> Done</button>
                                </center>
                            </div>
                        </form>
                    </section>';

                    if(isset($_POST['view_employee'])){
                        $sqllmsemployee	= $dblms->querylms("SELECT e.emply_id, e.emply_name, e.id_type, e.emply_joindate,
                                                            e.emply_phone, e.emply_email, e.emply_basicsalary, e.emply_photo,
                                                            d.dept_name,
                                                            dp.designation_name 
                                                            FROM ".EMPLOYEES." e      
                                                            INNER JOIN ".DEPARTMENTS." d ON d.dept_id = e.id_dept
                                                            INNER JOIN ".DESIGNATIONS." dp ON dp.designation_id = e.id_designation
                                                            WHERE e.emply_id = '".$id_emply."' AND e.emply_status = '1' AND e.id_campus = '".$id_campus."' 
                                                            AND e.id_dept = '".$id_dept."' LIMIT 1");
                        $value_emp = mysqli_fetch_array($sqllmsemployee);
                        echo'
                        <form action="salary.php" class="validate" method="post" accept-charset="utf-8" novalidate="novalidate">
                            <section class="panel panel-featured panel-featured-primary appear-animation fadeInRight appear-animation-visible" data-appear-animation="fadeInRight" data-appear-animation-delay="100" style="animation-delay: 100ms;">
                                <header class="panel-heading">
                                    <h2 class="panel-title">Make Payment</h2>
                                </header>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <section class="panel" style="box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);">
                                                <header class="panel-heading" style="border-bottom: 2px solid #0088cc;">
                                                    <h2 class="panel-title">Allowances</h2>
                                                </header>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <div class="col-sm-6 mt-md">
                                                            <input class="form-control" name="allowance_name[]" placeholder="Name Of Allowance" type="text">
                                                        </div>
                                                        <div class="col-sm-6 mt-md">
                                                            <input class="salary form-control" name="allowance_value[]" placeholder="Amount" id="allowance_amount" type="number">
                                                        </div>
                                                    </div>
                                                    <div id="add_new_allowance"></div>
                                                    <button type="button" class="btn btn-default mt-md" onclick="add_more_allowances()"><i class="fa fa-plus"></i> Add More</button>
                                                </div>
                                            </section>
                                        </div>                
                                        <div class="col-md-6">
                                            <section class="panel" style="box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);">
                                                <header class="panel-heading" style="border-bottom: 2px solid #0088cc;">
                                                    <h2 class="panel-title">Deductions</h2>
                                                </header>
                                                <div class="panel-body">

                                                    <div class="row">
                                                        <div class="col-sm-6 mt-md">
                                                            <input class="form-control" name="deduction_name[]" placeholder="Name Of Deductions" type="text">
                                                        </div>
                                                        <div class="col-sm-6 mt-md">
                                                            <input class="deduction form-control" name="deduction_value[]" placeholder="Amount" id="deduction_amount" type="number">
                                                        </div>
                                                    </div>
                                                    <div id="add_new_deduction"></div>
                                                    <button type="button" class="btn btn-default mt-md" onclick="add_more_deduction()"><i class="fa fa-plus"></i> Add More</button>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 col-sm-offset-6">
                                            <section class="panel" style="box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.15);">
                                                <header class="panel-heading" style="border-bottom: 2px solid #0088cc;">
                                                    <h2 class="panel-title">Salary Details</h2>
                                                </header>
                                                <div class="panel-body">
                                                    <table class="table h5 text-dark">
                                                        <tbody>
                                                            <tr class="b-top-none">
                                                                <td colspan="2">Basic Salary</td>
                                                                <td class="text-left">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">$</span>
                                                                        <input type="text" class="form-control" name="basic_salary" readonly="" value="'.$value_emp['emply_basicsalary'].'">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Total Allowance</td>
                                                                <td class="text-left">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">$</span>
                                                                        <input type="text" class="form-control" name="total_allowance" readonly="" id="total_allowance" value="0">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2">Total Deductions</td>
                                                                <td class="text-left">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">$</span>
                                                                        <input type="text" class="form-control" name="total_deduction" readonly="" id="total_deduction" value="0">
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            <tr class="h4">
                                                                <td colspan="2">Net Salary</td>
                                                                <td class="text-left">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon">$</span>
                                                                        <input type="text" class="form-control" name="net_salary" readonly="" id="net_salary" value="'.$value_emp['emply_basicsalary'].'">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="id_emply" value="'.$id_emply.'">
                                <input type="hidden" name="id_dept" value="'.$id_dept.'">
                                <input type="hidden" name="id_month" value="'.$id_month.'">
                                <input type="hidden" name="id_campus" value="'.$id_campus.'">
                                <div class="panel-footer text-right">
                                    <button type="submit" id="make_salary" name="make_salary" class="btn btn-primary"> Make Payment</button>
                                </div>
                            </section>
                        </form>';
                        ?>
                        <script type="text/javascript">
                            function add_more_allowances() {
                                var add_new = $('<div class="row"><div class="col-sm-6 mt-md"> <input class="form-control" name="allowance_name[]" placeholder="Name Of Allowance" type="text">\n\
                                </div><div class="col-sm-4 mt-md"> <input class="salary form-control" name="allowance_value[]" placeholder="Amount" type="number"></div>\n\
                                <div class="col-sm-2 mt-md text-right"><button type="button" class="btn btn-danger removeAL" ><i class="fa fa-times"></i> </button></div></div>');
                                $("#add_new_allowance").append( add_new );
                            }

                            $("#add_new_allowance").on('click', '.removeAL', function () {
                                $(this).parent().parent().remove();
                                check_sum();
                            });
                            
                            function add_more_deduction() {
                                var add_new = $('<div class="row"><div class="col-sm-6 mt-md"> <input class="form-control" name="deduction_name[]" placeholder="Name Of Deductions" type="text">\n\
                                </div><div class="col-sm-4 mt-md"> <input class="deduction form-control" name="deduction_value[]" placeholder="Amount" type="number"></div>\n\
                                <div class="col-sm-2 mt-md text-right"><button type="button" class="btn btn-danger removeDE"><i class="fa fa-times"></i> </button></div></div>');
                                $("#add_new_deduction").append( add_new );
                            }

                            $("#add_new_deduction").on('click', '.removeDE', function () {
                                $(this).parent().parent().remove();
                                check_sum();
                            });
                            
                            $(document).on("keyup", function () {
                                check_sum();
                            });
                            
                            function check_sum() {
                                var sum = 0;
                                var deduc = 0;
                                $(".salary").each(function () {
                                    sum += +$(this).val();
                                });

                                $(".deduction").each(function () {
                                    deduc += +$(this).val();
                                });
                                var ctc = $("#ctc").val();

                                $("#total_allowance").val(sum);
                                $("#total_deduction").val(deduc);
                                var net_salary = 0;
                                var basic = <?php echo $value_emp['emply_basicsalary'];?>;
                                net_salary = (basic + sum) - deduc;
                                $("#net_salary").val(net_salary);
                            }
                        </script>
                        <?php
                    }
                }
                echo'
            </div>
        </div>
    </section>';
    ?>
    <script type="text/javascript">
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
        function get_deptemployee(id_dept) {
			var id_campus = $("#id_campus").val();
            $.ajax({  
                type: "POST",  
                url: "include/ajax/get_dept-emply.php",  
                data: {
                     'id_dept'      : id_dept
                    ,'id_campus'    : id_campus
                },
                success: function(msg){  
                    console.log(id_dept);
                    $("#id_emply").html(msg); 
                }
            });  
        }

        // print invoice function
            function PrintElem(elem)
        {
            Popup($(elem).html());
        }
        
        function Popup(data)
        {
            var mywindow = window.open();
            mywindow.document.write('<html><head><title>Invoice</title>');
            mywindow.document.write('<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />');
            mywindow.document.write('<link rel="stylesheet" href="assets/stylesheets/invoice-print.css" />');
            mywindow.document.write('</head><body >');
            mywindow.document.write(data);
            mywindow.document.write('</body></html>');
            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10
        }
    </script>
    <?php 
}else{
    header("Location: dashboard.php");
}
include_once("include/footer.php");
?>