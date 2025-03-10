<section role="main" class="content-body">
  <header class="page-header"><h2><?=$data['title']?></h2></header>

  <!--- INCLUDING PAGE ---->
  <div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
      <header class="panel-heading">
        <a href="#createModal" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add New Exam Type</a>
        <h2 class="panel-title"><i class="fa fa-list"></i> Exam Types List</h2>
      </header>

      <div class="panel-body" style="width: 100%;">
        <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
          <thead>
          <tr>
            <th class='text-center'>Sr #</th>
            <th >Type Name</th>
            <th class="text-center">Type Status</th>
            <th width="100px;" class="center">Options</th>
          </tr>
          </thead>

          <tbody>

          <?php
          // Increment
          $i = 1;
          foreach ($data['exam_types'] as $exam_type) :
            ?>
            <tr>
              <td class="text-center"><?=$i?></td>
              <td><?=$exam_type->type_name?></td>
              <td  class="text-center">
                <?php
                echo $exam_type->type_status ? "<div class='badge bg-success'>Active</div>" :  "<div class='badge bg-danger'>In Active</div>";
                ?>
              </td>
              <td class=" center ">
                <a class="modal-with-move-anim btn btn-success btn-xs ml-xs" data-modal="#updateModal" data-load-html="true" data-fetch-url="<?=URLROOT . "/examtype/edit_html/$exam_type->type_id"?>" href="#updateModal"> <i class=" fa fa-edit"></i>
                </a>
                <a class="btn btn-danger btn-xs ml-xs" href="<?=route("examtype/delete/$exam_type->type_id")?>"> <i class=" fa fa-trash"></i>
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
      <form action="<?= URLROOT ?>/examtype/create" class="mb-lg validate" method="post" accept-charset="utf-8" novalidate="novalidate">
        <header class="panel-heading">
          <h4 class="panel-title">Create New Exam Type</h4>
        </header>

        <div class="panel-body">

          <div class="row">
            <div class="col-md-12 mx-auto">
              <div class="form-group">
                <label class="control-label">Exam Type Name <span class="required" aria-required="true">*</span></label>
                <input type="text" class="form-control" name="type_name" id="type_name" required="" title="Must Be Required" aria-required="true">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="form-group mt-md mb-sm">
                <label class="control-label">Status <span class="required">*</span></label>
                <div class="radio-custom radio-inline ml-md mt-sm">
                  <input type="radio" id="type_status" name="type_status" value="1" checked>
                  <label for="radioExample1">Active</label>
                </div>
                <div class="radio-custom radio-inline">
                  <input type="radio" id="type_status" name="type_status" value="0">
                  <label for="radioExample2">Inactive</label>
                </div>
              </div>
            </div>
          </div>

        </div>

        <footer class="panel-footer">
          <div class="row">
            <div class="col-md-12 text-right">
              <button class="btn btn-default modal-dismiss">Cancel</button>
              <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Add Exam Type</button>
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
