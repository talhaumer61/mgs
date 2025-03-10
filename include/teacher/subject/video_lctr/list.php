<?php
$sqllms_video = $dblms->querylms("SELECT  id, title, facebook_code, facebook_code,id_added
                                   	FROM ".VIDEO_LECTURE."
									WHERE id_session    = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
									AND id_class        = '".$_GET['class']."'
                                    AND id_subject IN (".$_GET['id'].",40)
									AND status          = '1' ORDER BY id ASC									
                                ");
if (mysqli_num_rows($sqllms_video) > 0){
    echo'
    <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
        <thead>
            <tr>
                <th width="40" class="center">Sr.</th>
                <th>Title</th>
                <th width="70" class="center">View</th>
                <th width="70" class="center">Actions</th>
            </tr>
        </thead>
        <tbody>';
            $srno = 0;
            while($value_video = mysqli_fetch_assoc($sqllms_video)) { 
                $srno++;
                echo'
                <tr>
                    <td class="center">'.$srno.'</td>
					<td>'.$value_video['title'].'</td>
                	<td class="center">
						<a href="#show_modal" class="modal-with-move-anim-pvs btn btn-primary btn-xs" onclick="showAjaxModalZoom(\'include/modals/video-lecture/modal_video_view.php?id='.$value_video['id'].'\');"><i class="glyphicon glyphicon-link"></i></a>
					</td>';
                    if($value_video['id_added'] == $_SESSION['userlogininfo']['LOGINIDA']){
                        echo'
                        <td class="center">
                            <a href="#show_modal" class="modal-with-move-anim-pvs dropdown-item" onclick="showAjaxModalZoom(\'include/modals/teacher_video_lectures/modal_update.php?edit_id='.$value_video['id'].'\');"><i class="fa fa-edit"></i> Edit</a>
                        </td>';
                    }
                    echo'
				</tr>';
            }
            echo'
        </tbody>
    </table>';
}else{
    echo'<h4 class="center">No Record Found</h4>';
}