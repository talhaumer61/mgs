<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){
	include_once("include/header.php");
    $addClassSQL = '';
    if(isset($_POST['id_class'])){
        if(cleanvars($_POST['id_class']) != 'all'){
            $addClassSQL = "AND f.id_class = '".cleanvars($_POST['id_class'])."'";
        }
    }
    
    $sql2 = "";
    $sql3 = "";
    $sql4 = "";
    $search_word = "";
    $type = "";
    $class = "";
    $filters = "";
    $id_campus = ((isset($_GET['id_campus']) && !empty($_GET['id_campus'])))? cleanvars($_GET['id_campus']): cleanvars($_SESSION['userlogininfo']['LOGINCAMPUS']);
    
    // FIlters
    if(isset($_GET['show'])){
        //  word
        if(isset($_GET['search_word']) && !empty($_GET['search_word']))
        {
            $sql2 = "AND (f.challan_no LIKE '%".cleanvars($_GET['search_word'])."%' OR st.std_name LIKE '%".cleanvars($_GET['search_word'])."%' OR c.class_name LIKE '%".cleanvars($_GET['search_word'])."%' OR st.std_rollno LIKE '%".cleanvars($_GET['search_word'])."%')";
            $search_word = cleanvars($_GET['search_word']);
        }

        // status
        if($_GET['type'])
        {
            $sql3 = "AND f.id_type = '".cleanvars($_GET['type'])."'";
            $type = cleanvars($_GET['type']);
        }
        //  class
        if($_GET['id_class'])
        {
            $sql4 = "AND f.id_class = '".cleanvars($_GET['id_class'])."'";
            $class = cleanvars($_GET['id_class']);
        }
        //  Section
        if($_GET['id_section'])
        {
            $sql5 = "AND f.id_section = '".cleanvars($_GET['id_section'])."'";
            $section = cleanvars($_GET['id_section']);
        }
    }
    echo '
    <title> Fee Defaulter List | '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Fee Defaulter List </h2>
        </header>
        <div class="row">
            <div class="col-md-12">
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-list"></i> Fee Defaulter List</h2>                        
                    </header>
                    <div class="panel-body">
                        <form action="#" method="GET" autocomplete="off">
                            <div class="form-group mb-sm">
                                <div class="col-sm-3">
                                    <label class="control-label">Search </label>
                                    <input type="search" name="search_word" id="search_word" class="form-control" value="'.$search_word.'" placeholder="Search">
                                </div>';
                                if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])):
                                    echo'
                                    <div class="col-md-3">
                                        <label class="control-label">Sub Campus</label>
                                        <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" onchange="get_class(this.value)"> 
                                            <option value="">Select</option>';
                                            $sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
                                                                                FROM ".CAMPUS." 
                                                                                WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
                                                                                AND campus_status	= '1'
                                                                                AND is_deleted		= '0'
                                                                                ORDER BY campus_id ASC");
                                            while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
                                                echo '<option value="'.$valSubCampus['campus_id'].'" '.(($valSubCampus['campus_id'] == $id_campus) ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
                                            }
                                            echo'
                                        </select>
                                    </div>';
                                endif;
                                echo'
                                <div class="'.((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])) ? 'col-md-2': 'col-md-3').'">
                                    <label class="control-label">Class </label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
                                        <option value="">Select</option>';
                                        $sqlCampLevel = $dblms->querylms("SELECT GROUP_CONCAT(l.level_classes) campus_classes
                                                                            FROM ".CAMPUS." c
                                                                            LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
                                                                            WHERE campus_id IN (".$id_campus.") ");
                                        $valCampLevel = mysqli_fetch_array($sqlCampLevel);
                                        $sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
                                                                            FROM ".CLASSES."
                                                                            WHERE class_status = '1'
                                                                            AND class_id IN (".$valCampLevel['campus_classes'].")
                                                                            ORDER BY class_id ASC");
                                        while($valuecls = mysqli_fetch_array($sqllmscls)) {
                                            echo '<option value="'.$valuecls['class_id'].'" '.(($valuecls['class_id'] == $class)? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
                                        }
                                        echo '
                                    </select>
                                </div>
                                <div class="'.((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])) ? 'col-md-2': 'col-md-3').'">
                                    <label class="control-label">Section </label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section">
                                        <option value="">Select</option>';
                                        if(!empty($_GET['id_class'])){
                                            $sqllmsSec	= $dblms->querylms("SELECT section_id, section_name 
                                                                            FROM ".CLASS_SECTIONS."
                                                                            WHERE id_class        = '".$_GET['id_class']."'
                                                                            AND section_status  = '1'
                                                                            AND is_deleted      = '0'
                                                                            AND id_campus       = '".$id_campus."'
                                                                            ORDER BY section_name ASC");
                                            while($valuecSec = mysqli_fetch_array($sqllmsSec)) {
                                                echo '<option value="'.$valuecSec['section_id'].'" '.($valuecSec['section_id']==$section ? 'selected' : '').'>'.$valuecSec['section_name'].'</option>';
                                            }
                                        }
                                        echo '
                                    </select>
                                </div>
                                <div class="'.((!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])) ? 'col-md-2': 'col-md-3').'">
                                    <label class="control-label">Challan </label>
                                    <select class="form-control" data-plugin-selectTwo data-width="100%" name="type">
                                        <option value="">Select</option>';
                                        foreach($challanType as $chllType){
                                            if($chllType['id'] <= 2){
                                                echo '<option value="'.$chllType['id'].'"'; if($type == $chllType['id']){ echo'selected';} echo'>'.$chllType['name'].'</option>';
                                            }
                                        }
                                        echo'
                                    </select>
                                </div>
                                <center>
                                    <button type="submit" name="show" class="btn btn-primary mt-md"><i class="fa fa-search"></i> Search Result</button>
                                </center>
                            </div>
                        </form>';
                        
                        //------------- Pagination ---------------------
                        $sqlstring	    = "";
                        $adjacents = 3;
                        if(!($Limit)) 	{ $Limit = 50; } 
                        if($page)		{ $start = ($page - 1) * $Limit; } else {	$start = 0;	}
                        
                        $sqllms	= $dblms->querylms("SELECT f.id
                                                        FROM ".FEES." f				   
                                                        INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
                                                        LEFT JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
                                                        INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
                                                        INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
                                                        WHERE f.status = '2' $sql2 $sql3 $sql4 $sql5
                                                        AND f.is_deleted != '1'
                                                        AND f.id_campus = '".cleanvars($id_campus)."'  
                                                        ORDER BY f.id DESC");
                                                        
                        $count = mysqli_num_rows($sqllms);
                        if($page == 0) { $page = 1; }						    //if no page var is given, default to 1.
                        $prev 		    = $page - 1;							//previous page is page - 1
                        $next 		    = $page + 1;							//next page is page + 1
                        $lastpage  		= ceil($count/$Limit);					//lastpage is = total pages / items per page, rounded up.
                        $lpm1 		    = $lastpage - 1;
                        
                        $sqllmsFeeDefaulter	= $dblms->querylms("SELECT f.id, f.status, f.id_month, f.challan_no, f.issue_date, f.due_date, f.paid_date, f.total_amount,
                                                                    c.class_name, cs.section_name, s.session_name, st.std_id, st.std_name, st.std_regno
                                                                    FROM ".FEES." f				   
                                                                    INNER JOIN ".CLASSES." c ON c.class_id = f.id_class	 	
                                                                    LEFT JOIN ".CLASS_SECTIONS." cs ON cs.section_id = f.id_section							 
                                                                    INNER JOIN ".SESSIONS." s ON s.session_id = f.id_session							 
                                                                    INNER JOIN ".STUDENTS." st ON st.std_id = f.id_std
                                                                    WHERE f.status = '2' $sql2 $sql3 $sql4 $sql5
                                                                    AND f.is_deleted != '1'
                                                                    AND f.id_campus = '".cleanvars($id_campus)."'  
                                                                    ORDER BY f.id DESC LIMIT ".($page-1)*$Limit .",$Limit");
                        if(mysqli_num_rows($sqllmsFeeDefaulter) > 0){
                            echo '
                            <table class="table table-bordered table-striped table-condensed mb-none">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">#</th>
                                        <th>Challan #</th>
                                        <th>Reg #</th>
                                        <th>Student</th>
                                        <th>Class</th>
                                        <th>Session</th>
                                        <th>Month</th>
                                        <th width="90px;">Issue Date</th>
                                        <th width="90px;">Due Date</th>
                                        <th width="70px;" style="text-align:center;">Status</th>
                                        <th>Total</th>
                                        <th width="100" style="text-align:center;">Options</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    $srno = 0;
                                    while($valueFee = mysqli_fetch_array($sqllmsFeeDefaulter)) {
                                        $srno++;
                                        echo '
                                        <tr>
                                            <td style="text-align:center;">'.$srno.'</td>
                                            <td>'.$valueFee['challan_no'].'</td>
                                            <td>'.$valueFee['std_regno'].'</td>
                                            <td>'.$valueFee['std_name'].'</td>
                                            <td>'.$valueFee['class_name'].' '; if($valueFee['section_name']){echo' ('.$valueFee['section_name'].') ';} echo'</td>
                                            <td>'.$valueFee['session_name'].'</td>
                                            <td>'; if($valueFee['id_month']){ echo' '.get_monthtypes($valueFee['id_month']).' ';} echo'</td>
                                            <td>'.$valueFee['issue_date'].'</td>
                                            <td>'.$valueFee['due_date'].'</td>
                                            <td style="text-align:center;">'.get_payments($valueFee['status']).'</td>
                                            <td>'.number_format(round($valueFee['total_amount'])).'</td>
                                            <td style="text-align:center;">
                                                <a class="btn btn-success btn-xs" style="text-align:center;" href="feechallanprint.php?id='.$valueFee['challan_no'].'" target="_blank"> <i class="fa fa-file"></i></a>
                                            </td>
                                        </tr>';
                                    }
                                    echo '
                                </tbody>
                            </table>';
                            //-------------- Pagination ------------------
                            if($count>$Limit) {
                                echo '
                                <div class="widget-foot">
                                <!--WI_PAGINATION-->
                                <ul class="pagination pull-right">';
                                //--------------------------------------------------
                                $current_page = strstr(basename($_SERVER['REQUEST_URI']), '.php', true);
                                $filters = 'search_word='.$search_word.'&id_campus='.$id_campus.'&type='.$type.'&id_class='.$class.'&show';
                                //--------------------------------------------------
                                $pagination = "";
                                if($lastpage > 1) { 
                                //previous button
                                if ($page > 1) {
                                    $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$prev.$sqlstring.'"><span class="fa fa-chevron-left"></span></a></a></li>';
                                }
                                //pages 
                                if ($lastpage < 7 + ($adjacents * 3)) { //not enough pages to bother breaking it up
                                    for ($counter = 1; $counter <= $lastpage; $counter++) {
                                        if ($counter == $page) {
                                            $pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
                                        } else {
                                            $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
                                        }
                                    }
                                } else if($lastpage > 5 + ($adjacents * 3)) { //enough pages to hide some
                                //close to beginning; only hide later pages
                                    if($page < 1 + ($adjacents * 3)) {
                                        for ($counter = 1; $counter < 4 + ($adjacents * 3); $counter++) {
                                            if ($counter == $page) {
                                                $pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
                                            } else {
                                                $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';
                                            }
                                        }
                                        $pagination.= '<li><a href="#"> ... </a></li>';
                                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
                                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
                                } else if($lastpage - ($adjacents * 3) > $page && $page > ($adjacents * 3)) { //in middle; hide some front and some back
                                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
                                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
                                        $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
                                        $pagination.= '<li><a href="#"> ... </a></li>';
                                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                                        if ($counter == $page) {
                                            $pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
                                        } else {
                                            $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
                                        }
                                    }
                                    $pagination.= '<li><a href="#"> ... </a></li>';
                                    $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lpm1.$sqlstring.'">'.$lpm1.'</a></li>';
                                    $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$lastpage.$sqlstring.'">'.$lastpage.'</a></li>';   
                                } else { //close to end; only hide early pages
                                    $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=1'.$sqlstring.'">1</a></li>';
                                    $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=2'.$sqlstring.'">2</a></li>';
                                    $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page=3'.$sqlstring.'">3</a></li>';
                                    $pagination.= '<li><a href="#"> ... </a></li>';
                                    for ($counter = $lastpage - (3 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
                                        if ($counter == $page) {
                                            $pagination.= '<li class="active"><a href="">'.$counter.'</a></li>';
                                        } else {
                                            $pagination.= '<li><a href="'.$current_page.'.php?'.$filters.'&page='.$counter.$sqlstring.'">'.$counter.'</a></li>';                 
                                        }
                                    }
                                }
                                }
                                //next button
                                if ($page < $counter - 1) {
                                    $pagination.= '<li><a href="'.$current_page.'.php?'.$filters .'&page='.$next.$sqlstring.'"><span class="fa fa-chevron-right"></span></a></li>';
                                } else {
                                    $pagination.= "";
                                }
                                    echo $pagination;
                                }
                                echo '
                                </ul>
                                <!--WI_PAGINATION-->
                                    <div class="clearfix"></div>
                                </div>';
                            }
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
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var datatable = $('#table_export').dataTable({
                        bAutoWidth : false,
                        ordering: false,
                    });
        });
        function get_class(id_campus) {  
            $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
            $.ajax({  
                type: "POST",  
                url: "include/ajax/get_class.php",  
                data: "id_campus="+id_campus,  
                success: function(msg){  
                    $("#id_class").html(msg); 
                    $("#loading").html(''); 
                }
            });  
        }
        function get_section(id_class) {  
			$("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
			var id_campus = $("#id_campus").val(); 
			$.ajax({  
				type: "POST", 
				url: "include/ajax/get_section.php",  
				data: {
						  'id_campus'   : id_campus
						, 'id_class' 	: id_class
					},
				success: function(msg){  
					$("#id_section").html(msg); 
					$("#loading").html(''); 
				}
			});  
		}
    </script>
    <?php
	include_once("include/footer.php");
}else{
    header("Location: dashboard.php");
}
?>