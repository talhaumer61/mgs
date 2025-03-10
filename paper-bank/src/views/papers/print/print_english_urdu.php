
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

    <!-- SKIN CSS -->
    <link rel="stylesheet" href="<?= PARENTROOT ?>/assets/stylesheets/skins/default.css" />
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
          .custom-left p{
            float: left;
          }
          .custom-right p{
            float: right;
          }
          .paragraph p
          {
            display: inline !important;
          }

          body {
              color: #777;
              font-family: "Open Sans", Arial, sans-serif;
              line-height: 22px;
              margin: 0;
              font-size: 13px;
          }

          .bg-red {
              background-color: #CB3F44;
              color: white;
          }

          .flex-container {
              display: flex;
              gap: 13px;
              align-items: center;
          }

          .bg-black {
              background-color: black;
              color: white;
          }

          .box {
              width: 25px;
              height: 25px;
              border: 2px solid black;
          }

          .border-bottom {
              border-bottom: 1px solid black;
          }

          a {
              color: #CCC;
          }

          a:hover,
          a:focus {
              color: #d9d9d9;
          }

          a:active {
              color: #bfbfbf;
          }
        
    </style>
   
</head>
<body>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12"> 
                <section class="panel panel-featured-primary">
                    <form action="#" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" autocomplete="off">

                            <div class="row mt-sm">
                              <div class="col-sm-12">
                                <div class="table-responsive">
                                  <table class="table table-condensed table-bordered table-striped table-hover text-center">
                                    <tbody>
                                      <tr>
                                      <td colspan="6"><h3><img src="https://mes.edu.pk/wp-content/uploads/2017/05/MES.jpg" alt="Logo" height="70" width="100"> &nbsp; Minhaj Education Board (Pakistan)</h3></td>
                                      </tr>
                                      <tr>
                                        <td colspan="6"><h4><?=$data['paper']->exam_type->exam_type_name?></h4></td>
                                      </tr>
                                      <tr>
                                        <td class="text-left bg-black">Subject</td>
                                        <td ><?=$data['paper']->subject->subject_name?></td>
                                        <td class="text-left bg-black">Class</td>
                                        <td ><?=$data['paper']->class->class_name?></td>
                                        <td class="text-left bg-black">Time Allowed</td>
                                        <td ><?=sprintf("%d:%02d", floor($data['paper']->paper_time/60), $data['paper']->paper_time%60);?> Hours</td>
                                      </tr>
                                      
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                            
                            <div class="row mt-sm">
                              <div class="col-sm-12">
                                <div class="table-responsive">
                                  <table class="table table-condensed table-bordered table-striped table-hover text-center mt-sm">
                                      <tbody>
                                        <tr>
                                          <td class="text-left bg-black">Q. No.</td>
                                          <td class="bg-black">1</td>
                                          <td class="bg-black">2</td>
                                          <td class="bg-black">3</td>
                                          <td class="bg-black">4</td>
                                          <td class="bg-black">5</td>
                                          <td class="bg-black">6</td>
                                          <td class="bg-black">7</td>
                                          <td class="bg-black">8</td>
                                          <td class="bg-black">9</td>
                                          <td class="bg-black">10</td>
                                          <td class="bg-black">Total Marks</td>
                                        </tr>
                                        <tr>
                                          <td class="text-left bg-black">Marks Obtained.</td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                          <td></td>
                                        </tr>

                                        <tr>
                                          <td class="text-left bg-black">Campus Name:</td>
                                          <td colspan="11"></td>
                                        </tr>

                                        <tr>
                                          <td class="text-left bg-black">Student Name:</td>
                                          <td colspan="6"></td>
                                          <td class="text-left bg-black"  colspan="2">Roll No:</td>
                                          <td colspan="3"></td>
                                        </tr>

                                        <tr>
                                          <td class="text-left bg-black">Total Marks:</td>
                                          <td colspan="6"><?=
                                          ($data['paper']->no_mcqs * $data['paper']->marks_mcq) + ($data['paper']->no_short_question * $data['paper']->marks_short_question) + ($data['paper']->no_long_question * $data['paper']->marks_long_question)
                                          ?></td>
                                          <td class="text-left bg-black"   colspan="2">Date:</td>
                                          <td colspan="3"></td>
                                        </tr>
                                        
                                      </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>

                            <div class="row mt-sm">
                              <div class="container-fluid">
                                <div class="col-sm-12">
                                  <h5 class="pull-left"> <b>1: Encircle the correct answer.</b> </h5>
                                  <h5 class="pull-right"><b>(<?=$data['paper']->no_mcqs * $data['paper']->marks_mcq?>)</b></h5>
                                </div>
                              </div>
                            </div>
                            <div class="row mt-sm">
                              <div class="container-fluid">
                                <div class="col-sm-12">
                                  <h5 dir="rtl" class="pull-right"> <b>1: صحیح جواب کا گھیراؤ کریں۔</b> </h5>
                                  <h5 class="pull-left"><b>(<?=$data['paper']->no_mcqs * $data['paper']->marks_mcq?>)</b></h5>
                                </div>
                              </div>
                            </div>
                            <div class="row mt-sm">
                              <div class="col-sm-12">
                                <div class="table-responsive">
                                  <table class="table table-condensed table-bordered table-striped table-hover text-center mt-sm">
                                      <thead>
                                          <tr>
                                              <th class="text-center" style="width:50px;">Sr#</th>
                                              <th class="text-center" style="width:650px;">Questions</th>
                                              <th class="text-center" style="width:100px;">(A)</th>
                                              <th class="text-center" style="width:100px;">(B)</th>
                                              <th class="text-center" style="width:100px;">(C)</th>
                                              <th class="text-center" style="width:100px;">(D)</th>
                                              
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php 
                                        $sr=1;
                                        foreach ($data['mcqQuestion'] as  $obj) :
                                         
                                         ?>
                                        <tr>
                                          <td><?=$sr?></td>
                                          <td>
                                            <span class="pull-left"><?=html_entity_decode($obj['question_english'])?></span><br>
                                            <span dir="rtl" class="pull-right" ><?=html_entity_decode($obj['question_urdu'])?></span>  
                                          </td>
                                          <td>
                                            <p><?=$obj['answers_options']['e_option_a']?></p>
                                            <p><?=$obj['answers_options']['u_option_a']?></p>
                                          </td>
                                          <td>
                                            <p><?=$obj['answers_options']['e_option_b']?></p>
                                            <p><?=$obj['answers_options']['u_option_b']?></p>
                                          </td>
                                          <td>
                                            <p><?=$obj['answers_options']['e_option_c']?></p>
                                            <p><?=$obj['answers_options']['u_option_c']?></p>
                                          </td>
                                          <td>
                                            <p><?=$obj['answers_options']['e_option_d']?></p>
                                            <p><?=$obj['answers_options']['u_option_d']?></p>
                                          </td>
                                        </tr>
                                        <?php
                                          $sr++;
                                          endforeach;
                                        ?>
                                                                    
                                      </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          <div class="paragraph">
                                
                            <div class="row mt-sm">
                              <div class="container-fluid">
                                <div class="col-sm-12">
                                  <h5 class="pull-left"><b>2: Write short answers of the following questions.</b> </h5>
                                  <h5 class="pull-right"><b>(<?=$data['paper']->no_short_question?>x<?=$data['paper']->marks_short_question?>=<?=$data['paper']->no_short_question * $data['paper']->marks_short_question?>) &nbsp; &nbsp;</b></h5>
                                </div>
                              </div>
                            </div>
                            <div class="row mt-sm">
                              <div class="container-fluid">
                                <div class="col-sm-12">
                                  <h5 dir="rtl" class="pull-right"><b>2: درج ذیل سوالات کے مختصر جوابات لکھیں۔</b> </h5>
                                  <h5 class="pull-left"><b>(<?=$data['paper']->no_short_question?>x<?=$data['paper']->marks_short_question?>=<?=$data['paper']->no_short_question * $data['paper']->marks_short_question?>) &nbsp; &nbsp;</b></h5>
                                </div>
                              </div>
                            </div>
                            <?php
                              $sr2=1;
                              foreach ($data['shortQuestion'] as $short):
                            ?>
                            <div class="row mt-sm">
                              <div class="container-fluid">
                                <div class="col-sm-12">
                                  <b>
                                    <span> &nbsp; &nbsp; (<?=numberToRoman($sr2)?>): <?=html_entity_decode($short['question_english'])?></span><br><br>
                                    <span dir="rtl" class="pull-right"> &nbsp; &nbsp; (<?=numberToRoman($sr2)?>): <?=html_entity_decode($short['question_urdu'])?></span>
                                  </b>
                                </div>
                              <br><br><br><br><br>
                                <?php 
                                  for ($i=0; $i < $data['paper']->no_lines_short_question; $i++) { 
                                    ?>
                                    <div class="col-sm-12">
                                      <div class="border-bottom"></div>
                                    </div><br><br>
                                  <?php
                                  }
                                ?>
                              </div>
                            </div>
                            <?php
                              $sr2++;
                              endforeach;
                            ?>
                                                          
                            <div class="row mt-sm">
                              <div class="container-fluid">
                                <div class="col-sm-12">
                                  <h5 class="pull-left"><b>3: Answers the following questions in detail.</b> </h5>
                                  <h5 class="pull-right"><b>(<?=$data['paper']->no_long_question?>x<?=$data['paper']->marks_long_question?>=<?=$data['paper']->no_long_question * $data['paper']->marks_long_question?>) &nbsp; &nbsp;</b></h5>
                                </div>
                              </div>
                            </div>
                            <div class="row mt-sm">
                              <div class="container-fluid">
                                <div class="col-sm-12">
                                  <h5 dir="rtl" class="pull-right"><b>3: درج ذیل سوالات کے تفصیل سے جواب دیں۔</b> </h5>
                                  <h5 class="pull-left"><b>(<?=$data['paper']->no_long_question?>x<?=$data['paper']->marks_long_question?>=<?=$data['paper']->no_long_question * $data['paper']->marks_long_question?>) &nbsp; &nbsp;</b></h5>
                                </div>
                              </div>
                            </div>
   
                              <?php
                                $sr3=1;
                                foreach($data['longQuestion'] as $long) :
                              ?>  
                                <div class="row mt-sm">
                                  <div class="container-fluid">
                                    <div class="col-sm-12">
                                      <b>
                                        <span> &nbsp; &nbsp; (<?=numberToRoman($sr3)?>): <?=html_entity_decode($long['question_english'])?></span><br><br>
                                        <span dir="rtl" class="pull-right"> &nbsp; &nbsp; (<?=numberToRoman($sr3)?>): <?=html_entity_decode($long['question_urdu'])?></span>
                                      </b>
                                    </div>
                                    <br><br><br><br><br>
                                    <?php
                                    for ($i=0; $i < $data['paper']->no_lines_long_question; $i++) { 
                                      ?>
                                    <div class="col-sm-12">
                                      <div class="border-bottom"></div>
                                    </div><br><br>
                                    <?php
                                    }
                                    ?>
                                  </div>
                                </div>
                                
                              <?php
                                $sr3++;
                                endforeach;
                              ?>
                            </div>
                              
                              
                              <!-- <div class="row mt-sm">
                                <div class="col-sm-12">
                                  <h3 class="text-center">Key Papers (<?=$data['paper']->subject->subject_name?>)</h3>
                                  <h5 class="text-center"><?=$data['paper']->class->c_name?> ------ <?=$data['paper']->subject->subject_name?></h5>
                                  <div class="table-responsive">
                                    <table class="table table-condensed table-bordered table-striped table-hover text-center">
                                      <tbody>
                                        <tr>
                                          <td>Q.No.1</td>
                                          <td>1</td>
                                          <td>Sun</td>
                                          <td>2</td>
                                          <td>Two</td>
                                          <td>3</td>
                                          <td>4</td>
                                          <td>Habitants</td>
                                          <td>5</td>
                                          <td>Plants</td>
                                        </tr>
                                        <tr>
                                          <td></td>
                                          <td>1</td>
                                          <td>Sun</td>
                                          <td>2</td>
                                          <td>Two</td>
                                          <td>3</td>
                                          <td>4</td>
                                          <td>Habitants</td>
                                          <td>5</td>
                                          <td>Plants</td>
                                        </tr>
                                        <tr>
                                          <td></td>
                                          <td>1</td>
                                          <td>Sun</td>
                                          <td>2</td>
                                          <td>Two</td>
                                          <td>3</td>
                                          <td>4</td>
                                          <td>Habitants</td>
                                          <td>5</td>
                                          <td>Plants</td>
                                        </tr>
                                        <tr>
                                          <td></td>
                                          <td>1</td>
                                          <td>Sun</td>
                                          <td>2</td>
                                          <td>Two</td>
                                          <td>3</td>
                                          <td>4</td>
                                          <td>Habitants</td>
                                          <td>5</td>
                                          <td>Plants</td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>
                                </div>
                              </div> -->

            </div>

        </div>
                        
                 
            </div>
        </div>
</body>
</html>
<script>
    window.print();
</script>