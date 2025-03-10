<?php
if(isset($_POST['promote_details'])){
    $session    = $_POST['id_session'];
    // $class      = $_POST['id_class'];
    $section    = $_POST['id_section'];
    $campus     = (isset($_POST['id_campus']) ? $_POST['id_campus'] : $_SESSION['userlogininfo']['LOGINCAMPUS']);
}else{
    $session    = "";
    // $class      = "";
    $section    = "";
    $campus     = $_SESSION['userlogininfo']['LOGINCAMPUS'];
}

if(isset($_POST['id_class']) && !empty($_POST['id_class'])){
    $array = explode('|', $_POST['id_class']);
    $class = cleanvars($array[0]);
}else{
    $class = "";
}
$col = (!empty($_SESSION['userlogininfo']['SUBCAMPUSES']) ? 'col-md-3' : 'col-md-4');
echo'
<section class="panel panel-featured panel-featured-primary">
    <form action="students_promote.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-random"></i> Select</h4>
        </div>
        <div class="panel-body">
            <div class="row mt-sm">';
                if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
                    echo'
                    <div class="'.$col.'">
                        <label class="control-label">Sub Campus</label>
                        <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_class(this.value)">
                            <option value="">Select</option>';
                            $sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
                                                            FROM ".CAMPUS." 
                                                            WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
                                                            AND campus_status	= '1'
                                                            AND is_deleted		= '0'
                                                            ORDER BY campus_id ASC");
                            while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
                                echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
                            }
                            echo'
                        </select>
                    </div>';
                }
                echo'
                <div class="'.$col.'">
                    <label class="control-label">Session <span class="required">*</span></label>
                    <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_session" name="id_session" required>
                        <option value="">Select</option>';
                        $sqllmsSession	= $dblms->querylms("SELECT session_id, session_name 
                                                            FROM ".SESSIONS."
                                                            WHERE session_id != '' AND session_status = '1'
                                                            ORDER BY session_id ASC");
                        while($valueSession 	= mysqli_fetch_array($sqllmsSession)) {
                            echo'<option value="'.$valueSession['session_id'].'" '.($session==$valueSession['session_id'] ? 'selected' : '').'>'.$valueSession['session_name'].'</option>';
                        }
                        echo '
                    </select>
                </div>
                <div class="'.$col.'">
                    <label class="control-label">Class <span class="required">*</span></label>
                    <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)">
                        <option value="">Select</option>';
                        $sqlLevelClasses    = $dblms->querylms("SELECT  l.level_classes
                                                                FROM ".CAMPUS." c
                                                                LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
                                                                WHERE campus_id = '".cleanvars($campus)."' LIMIT 1");
                        $valLevelClasses    = mysqli_fetch_array($sqlLevelClasses);

                        $sqllmsclass	= $dblms->querylms("SELECT class_id, class_name
                                                            FROM ".CLASSES."
                                                            WHERE class_id != '' AND class_status = '1'
                                                            AND class_id IN (".$valLevelClasses['level_classes'].")
                                                            ORDER BY class_id ASC");
                        while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
                            echo'<option value="'.$value_class['class_id'].'" '.($value_class['class_id'] == $class ? 'selected' : '').'>'.$value_class['class_name'].'</option>';
                        }
                        echo'
                    </select>
                </div>
                <div class="'.$col.'">
                    <label class="control-label">Section <span class="required">*</span></label>
                    <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" required>
                        <option value="">Select</option>';  
                        $sqllmssection	= $dblms->querylms("SELECT section_id, section_name 
                                                            FROM ".CLASS_SECTIONS." 
                                                            WHERE section_status = '1' AND is_deleted != '1'
                                                            AND id_class='".$class."' 
                                                            AND id_campus = '".$campus."' 
                                                            ORDER BY section_id ASC");
                        while($value_section = mysqli_fetch_array($sqllmssection)) {
                            echo'<option value="'.$value_section['section_id'].'" '.($value_section['section_id'] == $section ? 'selected' : '').'>'.$value_section['section_name'].'</option>';
                        }
                        echo'
                    </select>
                </div>
            </div>		
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-center">
                    <button type="submit" id="promote_details" name="promote_details" class="mr-xs btn btn-primary">Get Details</button>
                </div>
            </div>
        </footer>
    </form>
</section>';

if(isset($_POST['promote_details'])){
    //  GET CLASS DETAIL
    $sqllms_clsSec	= $dblms->querylms("SELECT c.class_name, s.section_name
                                        FROM ".CLASSES." c
                                        LEFT JOIN ".CLASS_SECTIONS." s ON s.id_class = c.class_id AND s.section_id = '".cleanvars($section)."'
                                        WHERE c.class_id != '' AND c.is_deleted != '1'
                                        AND c.class_id = '".$class."' ");
    $value_clsSec 	= mysqli_fetch_array($sqllms_clsSec);

    //  GET SESSION NAME DETAIL
    $sqllmsgetSession	= $dblms->querylms("SELECT session_name
                                        FROM ".SESSIONS."
                                        WHERE session_id != '' AND is_deleted != '1'
                                        AND session_id = '".$session."' ");
    $value_session 	= mysqli_fetch_array($sqllmsgetSession);

    //  GET STUDENT LIST
    $sqllms	= $dblms->querylms("SELECT std_id, std_status, std_name, std_fathername, std_gender, 
                                std_nic, std_phone, id_class, id_session,
                                std_rollno, std_regno, std_photo
                                FROM ".STUDENTS." 		
                                WHERE std_id != '' AND std_status = '1'  AND is_deleted != '1'
                                AND id_class = '".$class."' 
                                AND id_section = '".$section."'
                                AND id_session = '".$session."'
                                AND id_campus = '".$campus."'
                                ORDER BY std_rollno ");
    if(mysqli_num_rows($sqllms)>0){
        echo'
        <section class="panel panel-featured panel-featured-primary">
            <form action="students_promote.php" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <input type="hidden" name="class_from" value="'.$class.'"/>
                <input type="hidden" name="session_from" value="'.$session.'"/>
                <input type="hidden" name="id_campus" id="id_campus" value="'.$campus.'"/>
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-random"></i> Promote Students of <u>'.$value_clsSec['class_name'].' '.($section > 0 ? '('.$value_clsSec['section_name'].')' : '').' '.$value_session['session_name'].'</u></h4>
                </div>
                <div class="panel-body">
                    <div class="row mb-md">';
                        if(!empty($_SESSION['userlogininfo']['SUBCAMPUSES'])){
                            echo'
                            <div class="'.$col.'">
                                <label class="control-label">Sub Campus</label>
                                <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_campus" name="id_campus" title="Must Be Required" onchange="get_class(this.value)">
                                    <option value="">Select</option>';
                                    $sqlSubCampus	= $dblms->querylms("SELECT campus_id, campus_name 
                                                                    FROM ".CAMPUS." 
                                                                    WHERE campus_id IN (".$_SESSION['userlogininfo']['SUBCAMPUSES'].")
                                                                    AND campus_status	= '1'
                                                                    AND is_deleted		= '0'
                                                                    ORDER BY campus_id ASC");
                                    while($valSubCampus = mysqli_fetch_array($sqlSubCampus)) {
                                        echo '<option value="'.$valSubCampus['campus_id'].'" '.($valSubCampus['campus_id'] == $campus ? 'selected' : '').'>'.$valSubCampus['campus_name'].'</option>';
                                    }
                                    echo'
                                </select>
                            </div>';
                        }
                        echo'
                        <div class="'.$col.'">
                            <label class="control-label">Session <span class="required">*</span></label>
                            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_session" name="id_session" required>
                                <option value="">Select</option>';
                                $sqllmsSession	= $dblms->querylms("SELECT session_id, session_name 
                                                                    FROM ".SESSIONS."
                                                                    WHERE session_id != '' AND session_status = '1'
                                                                    ORDER BY session_id ASC");
                                while($valueSession 	= mysqli_fetch_array($sqllmsSession)) {
                                    echo'<option value="'.$valueSession['session_id'].'" '.($session==$valueSession['session_id'] ? 'selected' : '').'>'.$valueSession['session_name'].'</option>';
                                }
                                echo'
                            </select>
                        </div>
                        <div class="'.$col.'">
                            <label class="control-label">Class <span class="required">*</span></label>
                            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section_promote(this.value)" required>
                                <option value="">Select</option>';
                                $sqlLevelClasses    = $dblms->querylms("SELECT  l.level_classes
                                                                        FROM ".CAMPUS." c
                                                                        LEFT JOIN ".CAMPUS_LEVELS." l ON l.level_id = c.id_level
                                                                        WHERE campus_id = '".cleanvars($campus)."' LIMIT 1");
                                $valLevelClasses    = mysqli_fetch_array($sqlLevelClasses);

                                $sqllmsclass	= $dblms->querylms("SELECT class_id, class_name
                                                                    FROM ".CLASSES."
                                                                    WHERE class_id != '' AND class_status = '1'
                                                                    AND class_id IN (".$valLevelClasses['level_classes'].")
                                                                    ORDER BY class_id ASC");
                                while($value_class 	= mysqli_fetch_array($sqllmsclass)) {
                                    echo'<option value="'.$value_class['class_id'].'" '.($value_class['class_id'] == $class ? 'selected' : '').'>'.$value_class['class_name'].'</option>';
                                }
                                echo'
                            </select>
                        </div>
                        <div class="'.$col.'">
                            <div class="form-group">
                                <label class="control-label">Section <span class="required">*</span></label>
                                <select class="form-control" data-plugin-selectTwo data-width="100%" id="id_section_promote" name="id_section" required>
                                    <option value="">Select</option>';
                                    $sqlSection	= $dblms->querylms("SELECT section_id, section_name
                                                                        FROM ".CLASS_SECTIONS."
                                                                        WHERE section_id != '' AND section_status = '1'
                                                                        AND id_class = '".$class."'
                                                                        AND id_campus = '".$campus."' 
                                                                        ORDER BY section_id ASC");
                                    while($valSection = mysqli_fetch_array($sqlSection)) {
                                        echo'<option value="'.$valSection['section_id'].'" '.($valSection['section_id'] == $section ? 'selected' : '').'>'.$valSection['section_name'].'</option>';
                                    }
                                    echo'
                                </select>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-condensed mb-none">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th width= 40>Photo</th>
                                <th>Student Name</th>
                                <th>Father Name</th>
                                <th>Roll no</th>
                                <th>Phone</th>
                                <th>CNIC</th>
                                <th>Reason</th>
                                <th width="70px;" class="center">Promote</th>
                            </tr>
                        </thead>
                        <tbody>';
                            $srno = 0;
                            while($rowsvalues = mysqli_fetch_array($sqllms)) {
                                $srno++;
                                if($rowsvalues['std_photo']) { 
                                    $photo = "uploads/images/students/".$rowsvalues['std_photo']."";
                                }
                                else{
                                    $photo = "uploads/default-student.jpg";
                                }
                                echo '
                                <tr>
                                    <td class="center">'.$srno.'</td>
                                    <td><img src="'.$photo.'" style="width:40px; height:40px;"></td>
                                    <td>'.$rowsvalues['std_name'].'</td>
                                    <td>'.$rowsvalues['std_fathername'].'</td>
                                    <td>'.$rowsvalues['std_rollno'].'</td>
                                    <td>'.$rowsvalues['std_phone'].'</td>
                                    <td>'.$rowsvalues['std_nic'].'</td>
                                    
                                    <td><input type="text" class="form-control" name="reason['.$srno.']" id="reason" autocomplete="off" aria-required="true"></td>
                                    <td class="center">
                                        <div class="checkbox-custom checkbox-inline mt-sm">
                                            <input type="checkbox" name="is_promote['.$srno.']" checked>
                                            <label for="checkboxExample1"></label>
                                        </div>
                                        <input type="hidden" name="id_std['.$srno.']" value="'.$rowsvalues['std_id'].'">
                                    </td>
                                </tr>';
                            }
                            echo '
                        </tbody>
                    </table>
                </div>
                <footer class="panel-footer">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" id="promote_students" name="promote_students" class="mr-xs btn btn-primary">Promote</button>
                        </div>
                    </div>
                </footer>
            </form>
        </section>';
    }else{
        $_SESSION['msg']['title'] 	= 'Error';
        $_SESSION['msg']['text'] 	= 'No Record Found Against Selection';
        $_SESSION['msg']['type'] 	= 'error';
    }
}
?>