<?php 
//---------------------------------------------------------
	include "../../dbsetting/lms_vars_config.php";
	include "../../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../../functions/login_func.php";
	include "../../functions/functions.php";
	checkCpanelLMSALogin();
//---------------------------------------------------------
	$sqllms	= $dblms->querylms("SELECT  note
								   FROM ".SYLLABUS." 
								   WHERE syllabus_id = '".cleanvars($_GET['id'])."' AND syllabus_type = '1' LIMIT 1");
	$rowsvalues = mysqli_fetch_array($sqllms);
//---------------------------------------------------------
echo '
<script src="assets/javascripts/user_config/forms_validation.js"></script>
<script src="assets/javascripts/theme.init.js"></script>
<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-primary">
            <form action="#" class="form-horizontal" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <header class="panel-heading">
                    <h2 class="panel-title">
                        <i class="fa fa-info-circle"></i> Syllabus Detail
                        <a class="pull-right modal-dismiss" data-dismiss="modal"><i class="fa fa-window-close"></i></a>
                    </h2>
                </header>
                <div class="panel-body mb-sm">
                    <p>'.html_entity_decode($rowsvalues['note'], ENT_QUOTES, "UTF-8").'</p>
                </div>
            </form>
        </section>
    </div>
</div>';
?>