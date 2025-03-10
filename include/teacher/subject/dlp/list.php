<?php 
if(($view != 'add') && !isset($_GET['edit_id'])){
    $sqllmsdlp	= $dblms->querylms("SELECT s.syllabus_id, s.syllabus_term, s.syllabus_file, s.id_month, s.id_week, s.note, c.class_name, su.subject_name
                                        FROM ".SYLLABUS." s
                                        INNER JOIN ".CLASSES." c ON c.class_id = s.id_class
                                        INNER JOIN ".CLASS_SUBJECTS." su ON su.subject_id = s.id_subject
                                        WHERE s.id_session      = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
                                        AND s.id_class          = '".$_GET['class']."'
                                        AND s.id_subject IN (".$_GET['id'].",41)
                                        AND s.syllabus_status   = '1'
                                        AND s.syllabus_type     = '2'
                                        AND s.is_deleted        = '0'
                                        ORDER BY s.syllabus_id DESC
                                    ");
    if(mysqli_num_rows($sqllmsdlp) > 0){
        echo'
        <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
            <thead>
                <tr>
                    <th class="center" width="40">Sr.</th>
                    <th>Term</th>
                    <th>Month</th>
                    <th>Week</th>
                    <th width="100px;" class="center">Download</th>
                </tr>
            </thead>
            <tbody>';
                $sratt = 0;
                while($value_dlp = mysqli_fetch_assoc($sqllmsdlp)) { 
                    $sratt ++;
                    echo'
                    <tr>
                        <td class="center">'.$sratt.'</td>
                        <td>'.get_term($value_dlp['syllabus_term']).'</td>
                        <td>'.get_monthtypes($value_dlp['id_month']).'</td>
                        <td>'.get_week($value_dlp['id_week']).'</td>
                        <td class="center">';
                            if($value_dlp['note']){
                            echo'<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-info btn-xs" onclick="showAjaxModalZoom(\'include/modals/syllabus-dlp/modal_dlp_details.php?id='.$value_dlp['syllabus_id'].'\');"><i class="glyphicon glyphicon-eye-open"></i></a>';
                            }
                            echo'  
                            <a href="#show_modal" class="modal-with-move-anim-pvs btn btn-warning btn-xs" onclick="showAjaxModalZoom(\'include/modals/syllabus-dlp/modal_dlp_remarks.php?id='.$value_dlp['syllabus_id'].'&sub='.$_GET['id'].'&cls='.$_GET['class'].'\');"><i class="glyphicon glyphicon-comment"></i></a>
                            <a href="uploads/dlp/'.$value_dlp['syllabus_file'].'" download="DLP-'.get_monthtypes($value_dlp['id_month']).'-'.get_week($value_dlp['id_week']).'-'.$value_dlp['class_name'].'-'.$value_dlp['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i></a>
                        </td>
                    </tr>';
                }
                echo'
            </tbody>
        </table>';
    }else{
        echo'<h4 class="center">No Record Found</h4>';
    }
}