<?php
$sqllms	= $dblms->querylms("SELECT not_title, dated, not_description
										FROM ".NOTIFICATIONS." 
                                        WHERE not_status = '1' AND is_deleted != '1' AND id_type='1'
                                        AND to_campus = '1' ORDER BY not_id desc
										");
										
$rowsvalues = mysqli_fetch_array($sqllms);
if(($rowsvalues['not_title'] || $rowsvalues['not_description']) && $_SESSION['SHOWNOTIFICATION'] == 1)
{
    unset($_SESSION['SHOWNOTIFICATION']);
    echo'
    <div class="modal fade col-md-6 col-sm-10" id="myModal" style="position: absolute; left: 50%;top: 35%;transform: translate(-50%, -50%);">
        <section class="panel panel-featured panel-featured-primary">
            <header class="panel-heading">
                <h2 class="panel-title">
                    <span><i class="fa fa-bell"></i> '.$rowsvalues['not_title'].'</span>
                    <a class="close" data-dismiss="modal"><i class="fa fa-window-close"></i></a>
                </h2>
            </header>
            <div class="panel-body" style="padding: 1 rem; text-align:center; text-align: justify;">
                <h4>'.$rowsvalues['not_description'].'</h4>
            </div>
        </section>
    </div>
    ';
}
?>

<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>