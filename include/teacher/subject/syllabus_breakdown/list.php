<?php 
if(($view != 'add') && !isset($_GET['edit_id'])){
    $sqllmsdlp	= $dblms->querylms("SELECT syllabus_term, syllabus_file, note
                                   	FROM ".SYLLABUS."
									WHERE id_session    = '".$_SESSION['userlogininfo']['ACADEMICSESSION']."' 
									AND id_class        = '".$_GET['class']."'
                                    AND id_subject IN (".$_GET['id'].",41)
									AND syllabus_status = '1'
                                    AND syllabus_type   = '1'
								    ORDER BY syllabus_id DESC
                                    ");
    if(mysqli_num_rows($sqllmsdlp) > 0){
        echo'
        <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
            <thead>
                <tr>
                    <th width="40" class="center">Sr.</th>
                    <th>Term</th>
                    <th>Note</th>
                    <th width="100px;" class="center">Download</th>
                </tr>
            </thead>
            <tbody>';
                $sratt = 0;
                while($value_dlp = mysqli_fetch_assoc($sqllmsdlp)){ 
                    if($value_dlp['syllabus_term'] == 1){
                        $term = 'First';
                    }elseif($value_dlp['syllabus_term'] == 2){
                        $term = 'Second';
                    }
                    $sratt ++;
                    echo'
                    <tr>
                        <td class="center">'.$sratt.'</td>
                        <td>'.$term.'</td>
                        <td>'.html_entity_decode($value_dlp['note']).'</td>
                        <td class="center"><a href="uploads/dlp/'.$value_dlp['syllabus_file'].'" download="'.$value_dlp['session_name'].'-'.get_monthtypes($value_dlp['id_month']).'-'.get_week($value_dlp['id_week']).'-'.$value_dlp['class_name'].'-'.$value_dlp['subject_name'].'" class="btn btn-success btn-xs");"><i class="glyphicon glyphicon-download"></i></a></td>
                    </tr>';
                }
                echo'
            </tbody>
        </table>';
    }else{
        echo'<h4 class="center">No Record Found</h4>';
    }
}