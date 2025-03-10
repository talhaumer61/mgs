<section role="main" class="content-body">
  <header class="page-header"><h2><?=$data['title']?></h2></header>

  <!-- INCLUDEING PAGE -->
  <div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
      <header class="panel-heading">
<!--        <a href="#createModal" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add New Question Type</a>-->
        <h2 class="panel-title"><i class="fa fa-list"></i> Question Types List</h2>
      </header>

      <div class="panel-body" style="width: 100%;">
        <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
          <thead>
          <tr>
            <th class='center' width="50px;">Sr #</th>
            <th class="center" >Type Name</th>
            <th class="center" width="100px;">Type Status</th>
            <th width="70px;" class="center">Options</th>
          </tr>
          </thead>

          <tbody>

          <?php
          // Increment
          $i = 1;
          foreach ($data['question_types'] as $question_type) :
            ?>
            <tr>
              <td class='center'><?=$i?></td>
              <td class='center'><?=$question_type->question_type_name?></td>
              <td class='center'>
                <?php
                echo $question_type->question_type_status ? "<div class='badge bg-success'>Active</div>" :  "<div class='badge bg-danger'>In Active</div>";
                ?>
              </td>
              <td class=" center ">
                <a class="modal-with-move-anim btn btn-success btn-xs ml-xs" data-modal="#updateModal" data-load-html="true" data-fetch-url="<?=URLROOT . "/questiontype/edit_html/$question_type->question_type_id"?>" href="#updateModal"> <i class=" fa fa-edit"></i>
                </a>
              </td>
            </tr>
            <?php
            // Increment the count
            $i ++;
          endforeach; ?>
          </tbody>
        </table>
      </div>

    </section>
  </div>


  <!-- Create New Class -->
  <div id="createModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
      <form action="<?= URLROOT ?>/questiontype/create" class="mb-lg validate" method="post" accept-charset="utf-8" novalidate="novalidate">
        <header class="panel-heading">
          <h4 class="panel-title">Create New Question Type</h4>
        </header>

        <div class="panel-body">

          <div class="row">
            <div class="col-md-12 mx-auto">
              <div class="form-group">
                <label class="control-label">Question Type Name <span class="required" aria-required="true">*</span></label>
                <input type="text" class="form-control" name="question_type_name" id="question_type_name" required="" title="Must Be Required" aria-required="true">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="form-group mt-md mb-sm">
                <label class="control-label">Status <span class="required">*</span></label>
                <div class="radio-custom radio-inline ml-md mt-sm">
                  <input type="radio" id="question_type_status" name="question_type_status" value="1" checked>
                  <label for="radioQuestionple1">Active</label>
                </div>
                <div class="radio-custom radio-inline">
                  <input type="radio" id="question_type_status" name="question_type_status" value="0">
                  <label for="radioQuestionple2">Inactive</label>
                </div>
              </div>
            </div>
          </div>

        </div>

        <footer class="panel-footer">
          <div class="row">
            <div class="col-md-12 text-right">
              <button class="btn btn-default modal-dismiss">Cancel</button>
              <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Add Question Type</button>
            </div>
          </div>
        </footer>

      </form>
    </section>
  </div>

  <!-- Update Class -->
  <div id="updateModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
    </section>
  </div>


</section>
