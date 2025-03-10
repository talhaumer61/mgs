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
        <h4 class="panel-title"><i class="fa fa-plus-square"></i> Generated Paper</h4>
    </div>

    <div class="panel-body">
        <div class="paper">
            <div class="paper-body">
            <div class="row mt-sm">
            <div class="col-sm-12">
              <div class="table-responsive">
                <h4>Q No. 1: Circle the Correct Answer</h4>
                <table class="table table-condensed table-bordered table-striped table-hover text-center">
                  <thead>
                  <tr class="bg-dark">
                    <th class="text-center" style="width: 20px;" >Q.No</th>
                    <th class="text-center" style="width: 500px;" >Questions</th>
                    <th class="text-center">Option 1</th>
                    <th class="text-center">Option 2</th>
                    <th class="text-center">Option 3</th>
                    <th class="text-center">Option 4</th>
                    <th class="text-center">Page#</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                  <?php
                    $sr1 = 1;
                    foreach($data['mcq_questions'] as $obj) :
                      
                        ?>
                    

                    <tr>
                      <td><?=$sr1?></td>
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
              <div class="table-responsive">
                <h4>Q No. 2: Short Question</h4>
                <table class="table table-condensed table-bordered table-striped table-hover text-center">
                  <thead>
                  <tr class="bg-dark">
                    <th class="text-center" style="width: 20px;" >Sr#</th>
                    <th class="text-center" >Questions</th>
                    <th class="text-center">Page#</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sr2=1;
                      foreach($data['short_questions'] as $short) : 
                    ?>
                    
                    <tr>
                      <td><?=$sr2?></td>
                      <td>
                        <span class="pull-left"><?=html_entity_decode($short['question_english'])?></span><br>
                        <span dir="rtl" class="pull-right" ><?=html_entity_decode($short['question_urdu'])?></span>
                      </td>
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
                <h4>Q No. 3: Long Question</h4>
                <table class="table table-condensed table-bordered table-striped table-hover text-center">
                  <thead>
                  <tr class="bg-dark">
                    <th class="text-center" style="width: 20px;" >Sr#</th>
                    <th class="text-center" >Questions</th>
                    <th class="text-center">Page#</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sr3=1;
                      foreach($data['long_questions'] as $long) :
                    ?>
                     
                    <tr>
                    <td><?=$sr3?></td>
                      <td>
                        <span class="pull-left"><?=html_entity_decode($long['question_english'])?></span><br>
                        <span dir="rtl" class="pull-right" ><?=html_entity_decode($long['question_urdu'])?></span>
                      </td>
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
                <a type="submit" href="<?=URLROOT?>/papers/save_generated" class="mr-xs btn btn-primary">Save Paper</a>
            <?php endif; ?>
        </div>
    </div>

</section>
    </div>
  </div>
