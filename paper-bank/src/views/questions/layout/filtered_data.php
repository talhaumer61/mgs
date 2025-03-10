
<div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary">
        <div class="panel-heading">
          <h4 class="panel-title"><i class="fa fa-plus-square"></i>&nbsp;Filtered Questions</h4>
        </div>

        <div class="panel-body">
          <div class="row mt-sm">
            <div class="col-sm-12">
              <div class="table-responsive">
                <h4>Q No. 1: Circle the Correct Answer</h4>
                <table class="table table-condensed table-bordered table-striped table-hover text-center">
                  <thead>
                  <tr class="bg-dark">
                    <th class="text-center" style="width: 50px;" >Q.No</th>
                    <th class="text-center" >Questions</th>
                    <th class="text-center" style="width: 130px;">Option 1</th>
                    <th class="text-center" style="width: 130px;">Option 2</th>
                    <th class="text-center" style="width: 130px;">Option 3</th>
                    <th class="text-center" style="width: 130px;">Option 4</th>
                    <th class="text-center" style="width: 70px;">Page#</th>
                    <th class="text-center" style="width: 130px;">Answer</th>
                    <th class="text-center" style="width: 75px;">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    
                  <?php
                    $sr1 = 1;
                    foreach($data['obj_question'] as $obj) :
                      
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
                      <td>
                        <p><?=$obj['answers_options']['e_option_correct']?></p>
                        <p><?=$obj['answers_options']['u_option_correct']?></p>
                      </td>
                      <td class=" center ">
                        <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT?>/questions/edit/<?=$obj['question_id']?>"> <i class=" fa fa-edit"></i>
                        </a>
                        <a class="modal-with-move-anim btn btn-primary btn-xs ml-xs" data-modal="#deleteModal" data-load-html="true" data-fetch-url="<?=URLROOT?>/questions/delete_html/<?=$obj['question_id']?>" href="#deleteModal"> <i class=" fa fa-trash"></i></a>
                      </td>
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
                    <th class="text-center" style="width: 50px;" >Sr#</th>
                    <th class="text-center" >Questions</th>
                    <th class="text-center" style="width: 150px;">Page#</th>
                    <th class="text-center" style="width: 150px;">Question#</th>
                    <th class="text-center" style="width: 75px;">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sr2=1;
                      foreach($data['short_question'] as $short) : 
                    ?>
                    
                    <tr>
                      <td><?=$sr2?></td>
                      <td>
                        <span class="pull-left"><?=html_entity_decode($short['question_english'])?></span><br>
                        <span dir="rtl" class="pull-right" ><?=html_entity_decode($short['question_urdu'])?></span>
                      </td>
                      <td><?=$short['page_num']?></td>
                      <td><?=$sr2?></td>
                      <td class=" center ">
                        <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT?>/questions/edit/<?=$short['question_id']?>"> <i class=" fa fa-edit"></i>
                        </a>
                        <a class="modal-with-move-anim btn btn-primary btn-xs ml-xs" data-modal="#deleteModal" data-load-html="true" data-fetch-url="<?=URLROOT?>/questions/delete_html/<?=$short['question_id']?>" href="#deleteModal"> <i class=" fa fa-trash"></i></a>
                        
                        </a>
                      </td>
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
                    <th class="text-center" style="width: 50px;" >Sr#</th>
                    <th class="text-center" >Questions</th>
                    <th class="text-center" style="width: 150px;">Page#</th>
                    <th class="text-center"style="width: 150px;">Question#</th>
                    <th class="text-center" style="width: 75px;">Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sr3=1;
                      foreach($data['long_question'] as $long) :
                    ?>
                    
                    
                     
                    <tr>
                    <td><?=$sr3?></td>
                      <td>
                        <span class="pull-left"><?=html_entity_decode($long['question_english'])?></span><br>
                        <span dir="rtl" class="pull-right" ><?=html_entity_decode($long['question_urdu'])?></span>
                      </td>
                      <td><?=$long['page_num']?></td>
                      <td><?=$sr3?></td>
                      <td class=" center ">
                        <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT?>/questions/edit/<?=$long['question_id']?>"> <i class=" fa fa-edit"></i>
                        </a>
                        <a class="modal-with-move-anim btn btn-primary btn-xs ml-xs" data-modal="#deleteModal" data-load-html="true" data-fetch-url="<?=URLROOT?>/questions/delete_html/<?=$long['question_id']?>" href="#deleteModal"> <i class=" fa fa-trash"></i></a>
                        
                      </td>
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
         <!-- Delete -->
         <div id="deleteModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
          <section class="panel panel-featured panel-featured-primary">
          </section>
        </div>
      </section>
    </div>
  </div>