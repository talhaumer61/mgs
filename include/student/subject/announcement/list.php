<?php 
//if(($view != 'add') && !isset($_GET['edit_id'])){
//----------------------------------------------------- 
$sqllmsannouncement	= $dblms->querylms("SELECT ann_id, ann_status, ann_title, ann_dated
                                    FROM ".ANNOUNCEMENT."
                                    WHERE id_campus = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                    AND id_session = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
									AND id_section = '".$_GET['section']."' AND id_class = '".$_GET['class']."' 
									AND id_subject = '".$_GET['id']."'");
//-----------------------------------------------------
    if (mysqli_num_rows($sqllmsannouncement) > 0) {
    echo '
        <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
            <thead>
                <tr>
                    <th width=40px;>Sr #</th>
                    <th>Title</th>
                    <th class="center" width=150px;>Date</th>
                </tr>
            </thead>
            <tbody>';

    $sratt = 0;
    while($value_ann = mysqli_fetch_assoc($sqllmsannouncement)) { 
    //-----------------------------------------------------
    $sratt ++;
    //------------------------------------------------
    echo '
                <tr>
                    <td>'.$sratt.'</td>
                    <td>'.$value_ann['ann_title'].'</td>
                    <td class="center">'.date("D d M Y", strtotime($value_ann['ann_dated'])).'</td>
				</tr>';
    }
    //-----------------------------------------------------
    echo '
            </tbody>
        </table>';
    }
    else{
        echo'<h4 class="center">No Record Found</h4>';
    }
//}