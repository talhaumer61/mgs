<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

include_once("include/header.php");
if(($_SESSION['userlogininfo']['LOGINTYPE'] == '1' && in_array('1', $_SESSION['userlogininfo']['PERMISSIONS'])) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '1', 'view' => '1'))) {
    include_once("include/campus/students_promote/query.php");
    echo '
    <title>Student Panel | '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Students Promote</h2>
        </header>
        <div class="row">
            <div class="col-md-12">';
                include_once("include/campus/students_promote/promote.php");
                echo'
            </div>
        </div>
    </section>';
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
        <?php 
        if(isset($_SESSION['msg'])) { 
            echo 'new PNotify({
                    title	: "'.$_SESSION['msg']['title'].'"	,
                    text	: "'.$_SESSION['msg']['text'].'"	,
                    type	: "'.$_SESSION['msg']['type'].'"	,
                    hide	: true	,
                    buttons: {
                        closer	: true	,
                        sticker	: false
                    }
                });';
            unset($_SESSION['msg']);
        }
        ?>	
        var datatable = $('#table_export').dataTable({
            bAutoWidth : false,
            ordering: false,
        });
        });
        function get_class(id_campus) {  
            $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
            $.ajax({
                type: "POST",  
                url: "include/ajax/get_class.php",  
                data: {
                    'id_campus'	: id_campus
                },
                success: function(msg){  
                    $("#id_class").html(msg); 
                    $("#loading").html(''); 
                }
            });
        }
        function get_section(id_class){
            var id_campus = $("#id_campus").val(); 
            $.ajax({  
                type: "POST",  
                url: "include/ajax/get_section.php",
                data: {
                     'id_class'		: id_class
                    ,'id_campus'	: id_campus
                },  
                success: function(msg){  
                    $("#id_section").html(msg);
                }
            });  
        }
        function get_section_promote(id_class){
            var id_campus = $("#id_campus").val(); 
            $.ajax({  
                type: "POST",  
                url: "include/ajax/get_section.php",
                data: {
                     'id_class'		: id_class
                    ,'id_campus'	: id_campus
                },  
                success: function(msg){  
                    $("#id_section_promote").html(msg); 
                }
            });  
        }

    </script>
    <?php
}else{
	header("location: dashboard.php");
}
include_once("include/footer.php");
?>