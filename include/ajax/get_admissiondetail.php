<?php
include "../dbsetting/lms_vars_config.php";
include "../dbsetting/classdbconection.php";
$dblms = new dblms();
include "../functions/login_func.php";
include "../functions/functions.php";

if(isset($_POST['form_no'])) {
    $sqllms	= $dblms->querylms("SELECT * FROM ".ADMISSIONS_INQUIRY." WHERE form_no = '".$_POST['form_no']."' AND is_deleted != '1' AND id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' LIMIT 1");
    
    $rowsvalues = mysqli_fetch_array($sqllms);
    echo '
    <script src="assets/javascripts/user_config/forms_validation.js"></script>
    <script src="assets/javascripts/theme.init.js"></script>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">Student Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="std_name" id="std_name" value="'.$rowsvalues['name'].'" required title="Must Be Required" autofocus>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">Father Name <span class="required">*</span></label>
                <input type="text" class="form-control" name="std_fathername" id="std_fathername" value="'.$rowsvalues['fathername'].'" required title="Must Be Required" >
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">Gender <span class="required">*</span></label>
                <select name="std_gender" data-plugin-selectTwo  data-width="100%" class="form-control populate" required title="Must Be Required">
                    <option'; if($rowsvalues['gender'] == "Male"){echo'selected';} echo'value="Male">Male</option>
                    <option'; if($rowsvalues['gender'] == "Female"){echo'selected';} echo' value="Female" >Female</option>
                </select>
            </div>
        </div>	
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Family No <span class="text-danger">(Father CNIC)</span></label>
                <input type="text" class="form-control" name="std_familyno" id="std_familyno"">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">NIC / B-Form <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="std_nic" id="std_nic" required="">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Phone <span class="required">*</span></label>
                <input type="text" class="form-control" name="std_phone" id="std_phone" value="'.$rowsvalues['cell_no'].'" required title="Must Be Required">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Guardian</label>
                <select class="form-control" data-plugin-selectTwo data-width="100%"  name="id_guardian">
                    <option value="">Select</option>';
                    foreach($guardian as $value){
                        echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                    }
                    echo '  
                </select>
            </div>
        </div>            
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Whatsapp </label>
                <input type="text" class="form-control" id="std_whatsapp" name="std_whatsapp">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Date of Birth </label>
                <input type="text" class="form-control" name="std_dob" id="std_dob" data-plugin-datepicker="" required="" title="Must Be Required" aria-required="true">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Blood Group </label>
                <select class="form-control" data-plugin-selectTwo data-width="100%"  name="std_bloodgroup">
                    <option value="">Select</option>';
                    foreach($bloodgroup as $listblood){
                        echo '<option value="'.$listblood.'">'.$listblood.'</option>';
                    }
                    echo '
                </select>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group">
                <label class="control-label">Religion </label>
                <select class="form-control" data-plugin-selectTwo data-width="100%"  name="std_religion">
                    <option value="">Select</option>';
                    foreach($religion as $rel)
                    {
                        echo' <option value="'.$rel.'">'.$rel.'</option>';
                    }
                    echo'
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <label class="control-label">Is Hostelize</label>
            <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="is_hostel" name="is_hostel" title="Must Be Required">
                <option value="">Select</option>';
                foreach ($statusyesno as $hostel_status) {
                    echo '<option value="'.$hostel_status['id'].'">'.$hostel_status['name'].'</option>';
                }
                echo'
            </select>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Group </label>
                <select class="form-control" data-plugin-selectTwo data-width="100%"  name="id_group">
                    <option value="">Select</option>';
                        $sqllmscls	= $dblms->querylms("SELECT group_id, group_name 
                                            FROM ".GROUPS."
                                            WHERE group_status = '1'
                                            ORDER BY group_name ASC");
                        while($valuecls = mysqli_fetch_array($sqllmscls)) {
                    echo '<option value="'.$valuecls['group_id'].'">'.$valuecls['group_name'].'</option>';
                    }
                echo '
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <label class="control-label">Class <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
                <option value="">Select</option>';
                    $sqllmscls	= $dblms->querylms("SELECT class_id, class_status, class_name 
                                        FROM ".CLASSES."
                                        WHERE class_status = '1' 
                                        AND class_id IN (".$_SESSION['userlogininfo']['LOGINCAMPUSCLASSES'].")
                                        ORDER BY class_id ASC");
                    while($valuecls = mysqli_fetch_array($sqllmscls)) {
                echo '<option value="'.$valuecls['class_id'].'">'.$valuecls['class_name'].'</option>';
                }
            echo '
            </select>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label">Section </label>
                <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" required>
                    <option value="">Select Class First</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">Roll No.</label>
                <input type="text" class="form-control" name="std_rollno" id="std_rollno">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">Admission Date <span class="required" aria-required="true">*</span></label>
                <input type="text" class="form-control" name="std_admissiondate" id="std_admissiondate" data-plugin-datepicker="" required="" title="Must Be Required" aria-required="true">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">City</label>
                <input type="text" class="form-control" name="std_city" id="std_city">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">ID Card</label>
                <input type="file" class="form-control" name="std_idcard" id="std_idcard" accept="image/*, application/msword, application/pdf">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="control-label">Father ID Card</label>
                <input type="file" class="form-control" name="std_fatheridcard" id="std_fatheridcard" accept="image/*, application/msword, application/pdf">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">Birth Certificate</label>
                <input type="file" class="form-control" name="std_birthcertificate" id="std_birthcertificate" accept="image/*, application/msword, application/pdf">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">School Leaving Certificate</label>
                <input type="file" class="form-control" name="std_leavingcertificate" id="std_leavingcertificate" accept="image/*, application/msword, application/pdf">
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label class="control-label">Other Documents</label>
                <input type="file" class="form-control" name="std_otherdocuments" id="std_otherdocuments" accept="image/*, application/msword, application/pdf">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label class="control-label">Address </label>
                <textarea type="text" class="form-control" name="std_address" id="std_address">'.$rowsvalues['address'].'</textarea>
            </div>
        </div>
    </div>';
}
?>