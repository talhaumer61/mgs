<?php 
//error_reporting(0);
//session_start();
//--------------------------------------------
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
//--------------------------------------------
if(isset($_POST['id_class'])) {
	$class = $_POST['id_class']; 
//--------------------------------------------
echo '
    <div class="col-sm-4">
        <div class="form-group">
            <label class="control-label">Section <span class="required">*</span></label>
            <select class="form-control" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_section" name="id_section" required>
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
                                        FROM ".CLASS_SECTIONS."
                                        WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."'
                                        AND section_status = '1' AND id_class = '".$class."' AND is_deleted != '1'
                                        ORDER BY section_name ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                echo '<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
                }
            echo '
            </select>
        </div>
    </div>';
//---------------------------------------
}
?>