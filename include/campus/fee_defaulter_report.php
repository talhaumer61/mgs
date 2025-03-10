<?php
if(($_SESSION['userlogininfo']['LOGINTYPE'] == 1) || ($_SESSION['userlogininfo']['LOGINTYPE'] == 2) || Stdlib_Array::multiSearch($_SESSION['userroles'], array('right_name' => '71', 'view' => '1'))){
	echo '
        <title> Fee Defaulter List | '.TITLE_HEADER.'</title>
        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Fee Defaulter List </h2>
            </header>
            <div class="row">
                <div class="col-md-12">';
                    include_once("fee_defaulter_report/list.php");
                    echo '
                </div>
            </div>
        </section>';
    ?>
    <script type="text/javascript">
        function print_report(printResult) {
            document.getElementById('header').style.display = 'block';
            document.getElementById('printfooter').style.display = 'block';
            var printContents = document.getElementById(printResult).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            var css = `@media print {
                                        @page {
                                            size: A4 landscape;
                                            margin: 0;
                                            margin: 4mm 4mm 4mm 4mm;
                                        }
                                    }
                    `,
            head = document.head || document.getElementsByTagName('head')[0],
            style = document.createElement('style');
            style.type = 'text/css';
            style.media = 'print';
            if (style.styleSheet){
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }
            head.appendChild(style);
            window.print();
            document.body.innerHTML = originalContents;
            document.getElementById('header').style.display = 'none';
            document.getElementById('printfooter').style.display = 'none';
        }
        function get_section(id_class) {  
            $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
            var id_campus = $("#id_campus").val(); 
            $.ajax({  
                type: "POST", 
                url: "include/ajax/get_section.php",  
                data: {
                        'id_campus'   : id_campus
                        , 'id_class' 	: id_class
                    },
                success: function(msg){  
                    $("#id_section").html(msg); 
                    $("#loading").html(''); 
                }
            });  
        }
        function get_class(id_campus) {  
            $("#loading").html('<img src="images/ajax-loader-horizintal.gif"> loading...');  
            $.ajax({  
                type: "POST",  
                url: "include/ajax/get_class.php",  
                data: "id_campus="+id_campus,  
                success: function(msg){  
                    $("#id_class").html(msg); 
                    $("#loading").html(''); 
                }
            });  
        }
        jQuery(document).ready(function($) {
            var datatable = $('#table_export').dataTable({
                        bAutoWidth : false,
                        ordering: false,
                    });
        });
    </script>
    <?php
}
?>