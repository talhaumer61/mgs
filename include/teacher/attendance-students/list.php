<?php
error_reporting(0);
$today = date("Y-m-d");
$id_class   = ((isset($_GET['id_class']) && !empty($_GET['id_class']))? $_GET['id_class'] : $value_emp['id_class']);
$start_date = ((isset($_GET['start_date']) && !empty($_GET['start_date']))? $_GET['start_date'] : '');
$end_date   = ((isset($_GET['end_date']) && !empty($_GET['end_date']))? $_GET['end_date'] : '');

$sql1 = '';
$sql2 = '';
$sql3 = '';
$id_section = '';


if(isset($_GET['id_section']) && !empty($_GET['id_section'])){
    $id_section = $_GET['id_section'];
    $sql1 = 'AND a.id_section = '.$id_section.'';
}


if(!empty($_GET['start_date']) && !empty($_GET['end_date'])){
    $sql2 = " AND (a.dated BETWEEN '".date('Y-m-d' , strtotime($start_date))."' AND '".date('Y-m-d' , strtotime($end_date))."') ";
}

if(isset($_GET['attendtype']) && !empty($_GET['attendtype'])){
    $attendtype = $_GET['attendtype'];
    $sql3 = ' AND sad.status = '.$attendtype.'';
} else {
    $attendtype = '';
}

echo'
<section class="panel panel-featured panel-featured-primary">
    <form action="#" class="mb-lg validate" enctype="multipart/form-data" method="get" accept-charset="utf-8" autocomplete="off">
        <header class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-filter"></i>  Select Filters</h2>
        </header>
        <div class="panel-body">			
            <div class="row form-group mb-md">
                <div class="col-md-4">
                    <label class="control-label">Class</label>
                    <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_class" name="id_class" onchange="get_section(this.value)" title="Must Be Required">
                        <option value="">Select</option>';
                        $sqllmscls	= $dblms->querylms("SELECT class_id, class_name 
                                                        FROM ".CLASSES." 
                                                        WHERE class_status = '1'
                                                        AND class_id IN (".$_SESSION['userlogininfo'] ['LOGINCAMPUSCLASSES'].")
                                                        ORDER BY class_id ASC");
                        while($valuecls = mysqli_fetch_array($sqllmscls)) {
                            echo '<option value="'.$valuecls['class_id'].'" '.($valuecls['class_id'] == $_GET['id_class'] ? 'selected' : '').'>'.$valuecls['class_name'].'</option>';
                        }
                        echo'
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="control-label">Section</label>
                    <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="id_section" name="id_section" title="Must Be Required">';
                        $sqlSection	= $dblms->querylms("SELECT section_id, section_name 
                                                        FROM ".CLASS_SECTIONS."
                                                        WHERE id_class      = '".$id_class."'
                                                        AND section_status  = '1'
                                                        AND is_deleted      = '0'
                                                        AND id_campus IN (".$id_campus.")
                                                        ORDER BY section_name ASC");
                        if(mysqli_num_rows($sqlSection) > 0){
                            echo'<option value="">Select</option>';
                            while($valSection = mysqli_fetch_array($sqlSection)) {
                                echo '<option value="'.$valSection['section_id'].'" '.($valSection['section_id'] == $id_section ? 'selected' : '').'>'.$valSection['section_name'].'</option>';
                            }
                        }else{
                            echo '<option value="">No Record Found</option>';
                        }
                        echo'
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class=" control-label">Date</label>
                        <div class="input-daterange input-group" data-plugin-datepicker="" data-plugin-options="{&quot;format&quot;: &quot;dd-mm-yyyy&quot;}">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input type="text" class="form-control" value="'.$start_date.'" placeholder="d-m-Y" name="start_date">
                            <span class="input-group-addon">to</span>
                            <input type="text" class="form-control" value="'.$end_date.'" placeholder="d-m-Y" name="end_date" max="'.$today.'">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="control-label">Attendance Type</label>
                    <select class="form-control" title="Must Be Required" data-plugin-selectTwo data-width="100%" id="attendtype" name="attendtype" title="Must Be Required">
                        <option value="">Select</option>';
                        foreach (get_AttendenceType() as $key => $val) {
                            echo'
                            <option value="'.$key.'" '.($key == $attendtype ? 'selected' : '').'>'.$val.'</option>';
                        }
                        echo'
                    </select>
                </div>
            </div>
            <div class="col-md-12 text-center">
                <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary">Show Students</button>
            </div>
        </div>
    </form>
</section>';
if($value_emp['id_class']){
    $sqllmsattendance	= $dblms->querylms("SELECT a.id, a.dated, a.id_class, a.id_section, c.class_name, s.section_name
                                                FROM ".STUDENT_ATTENDANCE." a
                                                INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." sad ON sad.id_setup = a.id $sql3
                                                INNER JOIN ".CLASSES." c ON c.class_id = a.id_class
                                                INNER JOIN ".CLASS_SECTIONS." s ON s.section_id = a.id_section
                                                WHERE a.status    = '1'
                                                AND a.id_campus   = '".$_SESSION['userlogininfo']['LOGINCAMPUS']."' 
                                                AND a.id_session  = '".cleanvars($_SESSION['userlogininfo']['ACADEMICSESSION'])."'
                                                AND a.id_class IN (".$id_class.")
                                                $sql1
                                                $sql2
                                                GROUP BY a.id
                                                ORDER BY a.dated DESC
                                            ");
    echo'
    <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
            <a href="attendance_students.php?view=add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Attendance</a>
            <a href="print_attendance_students_report.php?id_class='.$id_class.'&id_section='.$id_section.'&start_date='.$start_date.'&end_date='.$end_date.'" target="_blank" class="btn btn-primary btn-xs mr-xs pull-right"><i class="fa fa-print"></i> Print</a>
            <h2 class="panel-title"><i class="fa fa-list"></i> Attendance List</h2>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed mb-none" id="table_export">
                <thead>
                    <tr>
                        <th width="40" class="center">Sr.</th>
                        <th>Date</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th class="center">Total Students</th>
                        <th class="center">Present</th>
                        <th class="center">Absent</th>
                        <th class="center">Leave</th>
                        <th class="center">Late</th>
                        <th width="100" class="center">Options</th>
                    </tr>
                </thead>
                <tbody>';
                    $sratt = 0;
                    while($value_att = mysqli_fetch_assoc($sqllmsattendance)) { 
                        $sratt ++;
                        $sqllmsprsent  = $dblms->querylms("SELECT COUNT(dt.id) AS totalpresent     
                                                                FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                                INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std
                                                                WHERE dt.status = '1' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                                AND std.std_status = '1'");
                        $valuepresent = mysqli_fetch_array($sqllmsprsent);

                        $sqllmsabsent  = $dblms->querylms("SELECT COUNT(dt.id) AS totalabsent     
                                                                FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                                INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std
                                                                WHERE dt.status = '2' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                                AND std.std_status = '1'");
                        $valueabsent = mysqli_fetch_array($sqllmsabsent);

                        $sqllmsholiday  = $dblms->querylms("SELECT COUNT(dt.id) AS totalholiday     
                                                                FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                                INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std
                                                                WHERE dt.status = '3' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                                AND std.std_status = '1'");
                        $valueholiday = mysqli_fetch_array($sqllmsholiday);

                        $sqllmslate  = $dblms->querylms("SELECT COUNT(dt.id) AS totallate     
                                                                FROM ".STUDENT_ATTENDANCE_DETAIL." dt 
                                                                INNER JOIN ".STUDENTS." std ON std.std_id = dt.id_std
                                                                WHERE dt.status = '4' AND dt.id_setup = '".cleanvars($value_att['id'])."' 
                                                                AND std.std_status = '1'");
                        $valuelate = mysqli_fetch_array($sqllmslate);

                        $total = $valuepresent['totalpresent'] + $valueabsent['totalabsent'] + $valueholiday['totalholiday'] + $valuelate['totallate'];
                        echo'
                        <tr>
                            <td class="center">'.$sratt.'</td>
                            <td>'.date("d M Y", strtotime($value_att['dated'])).'</td>
                            <td>'.$value_att['class_name'].'</td>
                            <td>'.$value_att['section_name'].'</td>
                            <td class="center">'.$total.'</td>
                            <td class="center">'.$valuepresent['totalpresent'].'</td>
                            <td class="center">'.$valueabsent['totalabsent'].'</td>
                            <td class="center">'.$valueholiday['totalholiday'].'</td>
                            <td class="center">'.$valuelate['totallate'].'</td>
                            <td class="center">';
                                // if($value_att['dated'] == $today){
                                    echo'<a class="btn btn-success btn-xs" href="attendance_students.php?id='.$value_att['id'].'&id_class='.$value_att['id_class'].'&id_section='.$value_att['id_section'].'&dated='.$value_att['dated'].'"> <i class="fa fa-pencil"></i></a>';
                                // }
                                echo'
                            </td>
                        </tr>';
                    }
                    echo'
                </tbody>
            </table>
        </div>
    </section>';
}else{
    header("location: dashboard.php");
}
?>