<style>
    .paper{
        display: block;
        width: 100%;
        color: black;
        font-family: Arial, serif ;
    }

</style>

<section role="main" class="content-body">

  <header class="page-header">
    <h2>Generated Paper</h2>
  </header>

  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary" >
    <div class="panel-heading">
        <h4 dir="rtl" class="panel-title"><i class="fa fa-plus-square"></i> جنریٹد پیپر</h4>
    </div>

    <div class="panel-body">
        <div class="paper">
            <div class="paper-body">
            <div class="row mt-sm">
            <div class="col-sm-12">
              <div class="table-responsive">
                <h4 dir="rtl">1: صحیح جواب کا گھیراؤ کریں۔</h4>
                <table dir="rtl" class="table table-condensed table-bordered table-striped table-hover text-center">
                  <thead>
                  <tr class="bg-dark">
                    <th class="text-center" style="width:50px;">سیریل#</th>
                    <th class="text-center" style="width:650px;">سوالات</th>
                    <th class="text-center" style="width:120px;">(الف)</th>
                    <th class="text-center" style="width:120px;">(ب	)</th>
                    <th class="text-center" style="width:120px;">(ج)</th>
                    <th class="text-center" style="width:120px;">(د)</th>
                    <th class="text-center">صفحہ#</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                  <?php
                    $sr1 = 1;
                    foreach($data['mcq_questions'] as $obj) :
                      
                        ?>
                    

                    <tr>
                      <td><?=$sr1?></td>
                      <td dir="rtl" class="text-right"><?=html_entity_decode($obj['question_urdu'])?></td>
                      <td><?=$obj['answers_options']['u_option_a']?></td>
                      <td><?=$obj['answers_options']['u_option_b']?></td>
                      <td><?=$obj['answers_options']['u_option_c']?></td>
                      <td><?=$obj['answers_options']['u_option_d']?></td>
                      <td><?=$obj['page_num']?></td>
                    </tr>
                    <?php
                      $sr1++;
                      endforeach;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="row mt-sm">
            <div class="col-sm-12">
              <div clas s="table-responsive">
                <h4 dir="rtl">2: درج ذیل سوالات کے مختصر جوابات لکھیں۔</h4>
                <table dir="rtl" class="table table-condensed table-bordered table-striped table-hover text-center">
                  <thead>
                  <tr class="bg-dark">
                    <th class="text-center" style="width:50px;">سیریل#</th>
                    <th class="text-center" style="width:650px;">سوالات</th>
                    <th class="text-center">صفحہ#</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sr2=1;
                      foreach($data['short_questions'] as $short) : 
                    ?>
                    
                    <tr>
                      <td><?=$sr2?></td>
                      <td class="text-right" dir="rtl"><?=html_entity_decode($short['question_urdu'])?></td>
                      <td><?=$short['page_num']?></td>
                    </tr>
                    <?php
                      $sr2++;
                      endforeach;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="row mt-sm">
            <div class="col-sm-12">
              <div class="table-responsive">
                <h4 dir="rtl">3: درج ذیل سوالات کے تفصیل سے جواب دیں۔</h4>
                <table dir="rtl" class="table table-condensed table-bordered table-striped table-hover text-center">
                  <thead>
                  <tr class="bg-dark">
                    <th class="text-center" style="width:50px;">سیریل#</th>
                    <th class="text-center" style="width:650px;">سوالات</th>
                    <th class="text-center">صفحہ#</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sr3=1;
                      foreach($data['long_questions'] as $long) :
                    ?>
                     
                    <tr>
                    <td><?=$sr3?></td>
                      <td  class="text-right" dir="rtl"><?=html_entity_decode($long['question_urdu'])?></td>
                      <td><?=$long['page_num']?></td>
                    </tr>
                    <?php
                      $sr3++;
                      endforeach;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

               
            </div>
        </div>

        <div class="col-md-12 text-center" style="margin-top: 50px">
            <?php if(isset($data['paper_id']) && $data['paper_id']) : ?>
                <a type="submit" href="<?=URLROOT?>/papers/save_generated/<?php echo $data['paper_id']?>" class="mr-xs btn btn-primary">Save Paper</a>
            <?php else: ?>
                <a type="submit" href="<?=URLROOT?>/papers/save_generated" class="mr-xs btn btn-primary">سیو پیپر</a>
            <?php endif; ?>
        </div>
    </div>

</section>
    </div>
  </div>
