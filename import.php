<?php 
require_once("include/dbsetting/lms_vars_config.php");
require_once("include/dbsetting/classdbconection.php");
require_once("include/functions/functions.php");
$dblms = new dblms();
require_once("include/functions/login_func.php");
checkCpanelLMSALogin();

include_once("include/header.php");
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
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
</script>
<?php
$id_campus = $_POST['id_campus'];

include_once("include/query_import.php");
echo '
<title> Import Excel Panel | '.TITLE_HEADER.'</title>
<section role="main" class="content-body">
    <header class="page-header">
        <h2>Import Excel Panel </h2>
    </header>
    <div class="row">
        <div class="col-md-12">';
            if (!isset($_POST['view_upload'])) {
                echo'
                <section class="panel panel-featured panel-featured-primary">
                    <header class="panel-heading">
                        <h2 class="panel-title"><i class="fa fa-filter"></i> Select</h2>
                    </header>
                    <div class="panel-body">
                        <form action="import.php" method="POST" enctype="multipart/form-data" accept-charset="utf-8">
                            <div class="row mb-sm">
                                <div class="col-md-4">
                                    <select class="form-control" name="id_campus">
                                        <option value="">Select Campus</option>
                                        <option value="158">primary1@pghss.edu.pk - 158</option>
                                        <option value="159">Primary2@pghss.edu.pk - 159</option>
                                        <option value="160">Primary3@pghss.edu.pk - 160</option>
                                        <option value="161">primaryboys@pghss.edu.pk - 161</option>
                                        <option value="162">middleboys@pghss.edu.pk - 162</option>
                                        <option value="163">middlegirls@pghss.edu.pk -	163</option>
                                        <option value="165">highboys@pghss.edu.pk - 165</option>
                                        <option value="164">highgirls@pghss.edu.pk - 164</option>
                                        <option value="167">collegeboys@pghss.edu.pk -	167</option>
                                        <option value="166">collegegirls@pghss.edu.pk - 166</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="file" class="form-control" id="file" name="file" />
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary form-control btn-md" name="view_upload">View File</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>';
            }
            if (isset($_POST['view_upload'])) {
                $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
                if(in_array($_FILES["file"]["type"],$allowedFileType)){
                    $targetPath = 'uploads/files/'.$_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
                    $Reader = new SpreadsheetReader($targetPath);
                    $sheetCount = count($Reader->sheets());
                    // $_SESSION['FILE']['FILE_NAME'] = $targetPath;
                    echo 'hello-2';
                    echo'
                    <section class="panel panel-featured panel-featured-primary">
                        <header class="panel-heading">
                            <form action="import.php" method="POST" enctype="multipart/form-data" accept-charset="utf-8">
                                <input type="hidden" name="id_campus" value="'.$id_campus.'"/>
                                <button type="submit" class="btn btn-primary btn-md pull-right btn-xs" name="upload_file">Upload Data</button>
                            </form>
                            <h2 class="panel-title"><i class="fa fa-list"></i>  Data In The File</h2>
                        </header>
                        <div class="panel-body">
                            <table class="table table-bordered table-striped table-condensed mb-none" id = "table_export">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>id_campus</th>
                                        <th>id_class</th>
                                        <th>id_section</th>
                                        <th>Roll No</th>
                                        <th>std_name</th>
                                        <th>std_fathername</th>
                                        <th>std_phone</th>
                                        <th>std_dob</th>
                                        <th>std_gender</th>
                                        <th>std_admissionddate</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    $fl = 0;
                                    $fs = 0;
                                    for($i=0;$i<$sheetCount;$i++){
                                        $Reader->ChangeSheet($i);
                                        foreach ($Reader as $Row)  {
                                            $fl++;
                                            if ($fl>1) {
                                                $fs++;
                                                echo'
                                                <tr>
                                                    <td>'.$fs.'</td>
                                                    <td>'.$id_campus.'</td>
                                                    <td>'.$Row[0].'</td>
                                                    <td>'.$Row[1].'</td>
                                                    <td>'.$Row[2].'</td>
                                                    <td>'.$Row[3].'</td>
                                                    <td>'.$Row[4].'</td>
                                                    <td>'.$Row[5].'</td>
                                                    <td>'.$Row[6].'</td>
                                                    <td>'.$Row[7].'</td>
                                                    <td>'.$Row[8].'</td>
                                                </tr>';
                                            }
                                        }
                                    }
                                    echo '
                                </tbody>
                            </table>
                        </div>
                    </section>';
                }
            }
            echo'
        </div>
    </div>
</section>';
?>