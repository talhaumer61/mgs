<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

include_once("include/header.php");
if(($_SESSION['userlogininfo']['LOGINTYPE']  == 1) || (arrayKeyValueSearch($_SESSION['userroles'], 'right_name', '45'))){
	require_once("include/headoffice/addeLogin/query.php");
    echo'
    <title> AD / DE Login | '.TITLE_HEADER.'</title>
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>AD / DE Login </h2>
        </header>
        <div class="row">
            <div class="col-md-12">';
                include_once("include/headoffice/addeLogin/list.php");
                include_once("include/modals/addeLogin/add.php");
                echo'
            </div>
        </div>';
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
            // GET EMPLOYEES
            $(document).on('change', '#id_type', function() {
                var id_type = $(this).val();                
                console.log(id_type);
                $.ajax({
                    url: "include/ajax/get_ad_de.php",
                    type: 'POST',
                    data: { 
                            id_type: id_type
                        },
                    success: function(data) {
                        $('#id_employee').html(data);
                    }
                });
            });
            // GET EMPLOYEE DETAIL
            $(document).on('change', '#id_employee', function() {
                var id_employee = $(this).val();
                console.log(id_employee);
                $.ajax({
                    url: "include/ajax/get_employeedetail.php",
                    type: 'POST',
                    data: { 
                            id_employee: id_employee
                        },
                    success: function(data) {
                        $('#getemployeedetail').html(data);
                    }
                });
            });
        </script>
        <?php 
        echo '
    </section>
    <!-- INCLUDES MODAL -->
    <script type="text/javascript">
        function showAjaxModalZoom( url ) {
            // PRELODER SHOW ENABLE / DISABLE
            jQuery( \'#show_modal\' ).html( \'<div style="text-align:center; "><img src="assets/images/preloader.gif" /></div>\' );
            // SHOW AJAX RESPONSE ON REQUEST SUCCESS
            $.ajax( {
                url: url,
                success: function ( response ) {
                    jQuery( \'#show_modal\' ).html( response );
                }
            } );
        }
    </script>

    <!-- (STYLE AJAX MODAL)-->
    <div id="show_modal" class="mfp-with-anim modal-block modal-block-primary mfp-hide"></div>
    <script type="text/javascript">
        function confirm_modal( delete_url ) {
            swal( {
                title: "Are you sure?",
                text: "Are you sure that you want to delete this information?",
                type: "warning",
                showCancelButton: true,
                showLoaderOnConfirm: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#ec6c62"
            }, function () {
                $.ajax( {
                    url: delete_url,
                    type: "POST"
                } )
                .done( function ( data ) {
                    swal( {
                        title: "Deleted",
                        text: "Information has been successfully deleted",
                        type: "success"
                    }, function () {
                        location.reload();
                    } );
                } )
                .error( function ( data ) {
                    swal( "Oops", "We couldn\'t\ connect to the server!", "error" );
                } );
            } );
        }
    </script>    
    <!-- INCLUDES BOTTOM -->';
}else{
	header("Location: dashboard.php");
}
include_once("include/footer.php");
?>