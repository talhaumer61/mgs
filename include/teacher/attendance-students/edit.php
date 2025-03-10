<?php 
$today = date("Y-m-d"); 

if(isset($_GET['id'])){
    $dated = date('Y-m-d' , strtotime(cleanvars($_POST['dated'])));
    $sqllms	= $dblms->querylms("SELECT a.id, a.status, a.dated, a.id_class, a.id_section, a.id_session, a.id_campus, 
                                    d.id_setup, d.id_std, d.status,
                                    s.std_id, s.std_status, s.std_name,
                                    s.std_fathername, s.std_regno, s.std_photo
                                    FROM ".STUDENT_ATTENDANCE." a
                                    INNER JOIN ".STUDENT_ATTENDANCE_DETAIL." d ON d.id_setup = a.id 
                                    INNER JOIN ".STUDENTS." s ON s.std_id = d.id_std
                                    
                                    WHERE a.id = '".$_GET['id']."' 
                                ");
    if(mysqli_num_rows($sqllms) > 0){
        echo'
        <div id="" class="" style=" overflow: auto;">
            <section class="panel panel-featured panel-featured-primary appear-animation" data-appear-animation="fadeInRight" data-appear-animation-delay="100">
                <form action="attendance_students.php" id="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-users"></i> <span class="hidden-xs">Students List</h2>
                    </header>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed mb-none ">
                                <thead>
                                    <tr>
                                        <th width="40" class="center">Sr.</th>
                                        <th width="40">Photo</th>
                                        <th>Full Name </th>
                                        <th>Regno </th>
                                        <th width="40%">Status </th>
                                    </tr>
                                </thead>
                                <tbody>';        
                                    $srno = 0;        
                                    while($rowsvalues = mysqli_fetch_array($sqllms)) {        
                                        $srno++;        
                                        echo'
                                        <input type="hidden" name="id" value="'.$rowsvalues['id'].'">
                                        <tr>
                                            <td class="center">'.$srno.'</td>
                                            <td class="center"> <img src="uploads/images/students/'.$rowsvalues['std_photo'].'" width="35" height="35"</td> 
                                                <input type="hidden" name="std_id['.$srno.']" id="std_id['.$srno.']" value="'.$rowsvalues['id_std'].'">
                                            
                                            <td>'.$rowsvalues['std_name'].' '.$rowsvalues['std_fathername'].'</td>
                                            <td>'.$rowsvalues['std_regno'].'</td>
                                            <td>
                                                <div class="radio-custom radio-success radio-inline">
                                                    <input type="radio" id="status['.$srno.']" name="status['.$srno.']" value="1"'; if($rowsvalues['status'] == 1) {echo' checked';}echo'>
                                                    <label for="pstatus_'.$srno.'">Present</label>
                                                </div>
                                                <div class="radio-custom radio-danger radio-inline">
                                                    <input type="radio" id="status['.$srno.']" name="status['.$srno.']" value="2"'; if($rowsvalues['status'] == 2) {echo' checked';}echo'>
                                                    <label for="astatus_'.$srno.'">Absent</label>
                                                </div>
                                                <div class="radio-custom radio-info radio-inline">
                                                    <input type="radio" id="status['.$srno.']" name="status['.$srno.']" value="3"'; if($rowsvalues['status'] == 3) {echo' checked';}echo'>
                                                    <label for="hstatus_'.$srno.'">Leave</label>
                                                </div>
                                                <div class="radio-custom radio-inline">
                                                    <input type="radio" id="status['.$srno.']" name="status['.$srno.']" value="4"'; if($rowsvalues['status'] == 4) {echo' checked';}echo'>
                                                    <label for="lstatus_'.$srno.'">Late</label>
                                                </div>
                                            </td>
                                        </tr>';
                                    }
                                    echo'
                                    <input type="hidden" name="id" value="'.$_GET['id'].'">
                                    <input type="hidden" name="class" value="'.$_GET['id_class'].'">	
                                    <input type="hidden" name="section" value="'.$_GET['id_section'].'">	
                                    <input type="hidden" name="dated" value="'.$_GET['date'].'">			
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <center>
                            <button type="submit" class="btn btn-primary" id="update_attendance" name="update_attendance">
                                <i class="fa fa-refresh"></i> Update Attendance</button>
                        </center>
                    </div>
                </form>
            </section>
        </div>';
    }
}else{
    header("location: attendance_students.php");
}
?>