
<!DOCTYPE html>
<html lang="en">
<head>
    <title> Admin Panel | School Management System</title>
    <!-- BASIC -->
    <meta charset="UTF-8">
    <meta name="keywords" content="School Management Software" />
    <meta name="description" content="School Management System (ERP)">
    <meta name="author" content="BFTech | Beyond Future Technologies.">
    <!-- MOBILE METAS -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- WEB FONTS  -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/bootstrap-switch/css/bootstrap-switch.min.css" />
    <!-- DATATABLES PAGE CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/vendor/jquery-datatables-bs3/assets/css/datatables.css" />

    <!-- THEME CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/stylesheets/theme.css" />

    
    <style>
        html,
          body {
              background: #ecedf0;
              width: 100%;
          }
          
          html {
              font-size: 10px;
              overflow-x: hidden !important;
              overflow-y: scroll !important;
          }
          

          body {
              color: #777;
              font-family: "Open Sans", Arial, sans-serif;
              line-height: 22px;
              margin: 0;
              font-size: 13px;
          }


          
    </style>
   
</head>
<body>
<div class="row mt-sm">
  <div class="col-sm-4 col-xs-3"></div>
  <div class="col-sm-4 col-xs-6">
    <h3 class="text-center">Key Papers(<?=$data['paper']->subject->subject_name?>)</h3>
    <h5 class="text-center"><?=$data['paper']->class->class_name?> ------ <?=$data['paper']->subject->subject_name?></h5>
    <h5 class="text-center"><b>Q No. 1</b></h5>
    <div class="table-responsive">
      <table class="table table-condensed table-bordered table-striped table-hover text-center">
        <thead>
          <th class="text-center" style="width: 100px;">Q no.</th>
          <th class="text-center">Answer</th>
        </thead>
        <tbody>
          
            <?php 
              $sr=1;
              foreach ($data['mcqQuestion'] as  $obj) :
                
                ?>
                <tr>
                  <td><?=$sr?></td>
                  <td><?=$obj['answers_options']['u_option_correct']?></td>
                </tr>
                
              <?php
                $sr++;
                endforeach;
              ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-sm-4 col-xs-4"></div>
</div>
</body>
</html>
<script>
    window.print();
</script>