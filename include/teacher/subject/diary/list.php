<?php 
if(($view != 'add') && !isset($_GET['edit_id'])){
    $sqllmsannouncement	= $dblms->querylms("SELECT id, status, note, dated
                                            FROM ".DIARY."
                                            WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                            AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                            AND id_teacher = '".$value_emp['emply_id']."' 
                                            AND id_section = '".$_GET['section']."' AND id_class = '".$_GET['class']."' 
                                            AND id_subject = '".$_GET['id']."'");
    if(mysqli_num_rows($sqllmsannouncement) > 0) {
        echo'
        <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
            <thead>
                <tr>
                    <th width="40" class="center">Sr.</th>
                    <th>Note</th>
                    <th class="center">Date</th>
                    <th class="center">Status</th>
                    <th width="100" class="center">Options</th>
                </tr>
            </thead>
            <tbody>';
                $sratt = 0;
                while($value_ann = mysqli_fetch_assoc($sqllmsannouncement)) { 
                    $sratt ++;
                    echo'
                    <tr>
                        <td class="center">'.$sratt.'</td>
                        <td>'.$value_ann['note'].'</td>
                        <td class="center">'.$value_ann['dated'].'</td>
                        <td class="center">'.get_status($value_ann['status']).'</td>
                        <td class="center"><a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/diary/modal_update.php?edit_id='.$value_ann['id'].'\');"><i class="glyphicon glyphicon-edit"></i> Edit</a></td>
                    </tr>';
                }
                echo'
            </tbody>
        </table>';
    }else{
        echo'<h4 class="center">No Record Found</h4>';
    }
}