<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
$sqllms	= $dblms->querylms("SELECT emply_id
                                FROM ".EMPLOYEES."
                                WHERE id_loginid = '".$_SESSION['userlogininfo']['LOGINIDA']."'
                                AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' LIMIT 1");
$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
$sqllms_syllabus = $dblms->querylms("SELECT cover_percentage, reamarks
                                FROM ".SYLLABUS_REAMRKS."
                                WHERE id_syllabus = '".cleanvars($_GET['id'])."' 
                                AND id_teacher = '".$rowsvalues['emply_id']."'
                                AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                ORDER BY id DESC LIMIT 1");
$value_syllabus = mysqli_fetch_array($sqllms_syllabus);
//---------------------------------------------------------
if(mysqli_num_rows($sqllms_syllabus) > 0){
    $covered = " value=".$value_syllabus['cover_percentage']." readonly";
    $remark = "  value=".$value_syllabus['reamarks']." readonly";
}
else{
    $covered = "";
    $remark = "";
}
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-primary">
            <form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <input type="hidden" name="id_syllabus" id="id_syllabus" value="'.cleanvars($_GET['id']).'">
                <input type="hidden" name="id_teacher" id="id_teacher" value="'.$rowsvalues['emply_id'].'">
                <input type="hidden" name="id_subject" id="id_subject" value="'.$_GET['sub'].'">
                <input type="hidden" name="id_class" id="id_class" value="'.$_GET['cls'].'">
                <header class="panel-heading">
                    <h2 class="panel-title">
                        <i class="fa fa-comment"></i> DLP Remarks
                        <a class="pull-right modal-dismiss" data-dismiss="modal"><i class="fa fa-window-close"></i></a>
                    </h2>
                </header>
                <div class="panel-body mb-sm">
                    <div class="form-group mb-md">
                        <div class="col-md-12 mb-sm">
                            <label class="control-label">DLP Covered (%) <span class="required">*</span></label>
                            <input type="number" class="form-control" name="cover_percentage" id="cover_percentage" '.$covered.' required title="Must Be Required"/>
                        </div>
                        <div class="col-md-12">
                            <label class="control-label">Reamrks</label>
                            <textarea class="form-control" name="reamarks" id="reamarks" '.$remark.'></textarea>
                        </div>
                    </div>
                </div>';
                if(mysqli_num_rows($sqllms_syllabus) < 0){
                    echo'
                    <footer class="panel-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary" id="submit_remarks" name="submit_remarks">Save</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>';
                }
            echo'
            </form>
        </section>
    </div>
</div>';
//---------------------------------------------------------
?>