<?php 

echo '
<title> Dashboard | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
	<header class="page-header">
		<h2>Subjects</h2>
	</header>
    <!-- INCLUDEING PAGE -->
    <style>
        a:link {text-decoration: none;}
    </style>
    <div class="row">';  
        //NOTIFICATION MODAL START
        $sqllms	= $dblms->querylms("SELECT not_title, dated, not_description
                                        FROM ".NOTIFICATIONS." 
                                        WHERE not_status = '1' 
                                        AND is_deleted != '1' 
                                        AND to_adde = '1' 
                                        AND id_campus = '0'
                                        AND DATEDIFF(date_from, CURDATE()) <= 0
                                        AND DATEDIFF(date_to, CURDATE()) >= 0 
                                        ORDER BY not_id desc
                                    ");
                                                
        $rowsvalues = mysqli_fetch_array($sqllms);
        if($rowsvalues['not_title'] || $rowsvalues['not_description'])
        {
            echo'
            <div class="modal fade col-md-6 col-sm-10" id="myModal" style="position: absolute; left: 50%;top: 35%;transform: translate(-50%, -50%);">
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title">
                            <span style="font-size: 30px; line-height: 30px;"><i class="fa fa-bell"></i> '.$rowsvalues['not_title'].'</span>
                            <a class="close" data-dismiss="modal"><i class="fa fa-window-close"></i></a>
                        </h2>
                    </header>
                    <div class="panel-body" style="height: 200px; line-height: 30px; padding: 20px; text-align:center; text-align: justify;">
                        <h3>'.$rowsvalues['not_description'].'</h3>
                    </div>
                </section>
            </div>';
        }
        include_once("dashboard/inspection_schedule.php");
        echo'
    </div>
</section>';
?>
<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>