<?php 
	include "../dbsetting/lms_vars_config.php";
	include "../dbsetting/classdbconection.php";
	$dblms = new dblms();
	include "../functions/login_func.php";
	include "../functions/functions.php";
if(isset($_POST['id_class'])) {
	$class = $_POST['id_class'];

    if(!empty($_POST['id_campus'])){
        $id_campus = $_POST['id_campus'];
    } else{
        $id_campus = $_SESSION['userlogininfo']['LOGINCAMPUS'];
    }
    echo '
    <div class="form-group">
        <label class="col-md-2 control-label">Section <span class="required">*</span></label>
            <div class="col-md-9">
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" id="id_section" name="id_section">';
                $sqllmscls	= $dblms->querylms("SELECT section_id, section_name 
                                            FROM ".CLASS_SECTIONS."
                                            WHERE id_campus = '".$id_campus."' 
                                            AND section_status = '1' AND is_deleted != '1' AND id_class = '".$class."'
                                            ORDER BY section_name ASC");
                if(mysqli_num_rows($sqllmscls) > 0){
                    echo '<option value="">Select</option>';
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                        echo '<option value="'.$valuecls['section_id'].'">'.$valuecls['section_name'].'</option>';
                    } 
                }else{
                    echo '<option value="">No Section Found</option>';
                }
            echo '
            </select>
        </div>
    </div>';
}
?>