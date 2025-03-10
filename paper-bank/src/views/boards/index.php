<section role="main" class="content-body">
  <header class="page-header"><h2><?=$data['title']?></h2></header>

  <!-- INCLUDEING PAGE -->
  <div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
      <header class="panel-heading">
        <a href="#createModal" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Add New Board</a>
        <h2 class="panel-title"><i class="fa fa-list"></i> Board List</h2>
      </header>

      <div class="panel-body" style="width: 100%;">
        <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
          <thead>
            <tr>
              <th class='text-center' width="50px;" >Sr #</th>
              <th class='text-center' >Board Name</th>
              <th class='text-center' width="150px;">Board Status</th>
              <th class='text-center' width="100px;" class="center">Options</th>
            </tr>
          </thead>

          <tbody>

          <?php
          // Increment
          $i = 1;
          foreach ($data['boards'] as $board) :
            ?>
            <tr>
              <td class='text-center'><?=$i?></td>
              <td class='text-center'><?=$board->board_name?></td>
              <td class='text-center'>
                <?php
                  echo $board->board_status ? "<div class='badge bg-success'>Active</div>" :  "<div class='badge bg-danger'>In Active</div>";
                ?>
              </td>
              <td class='text-center' class=" center ">
                <a class="modal-with-move-anim btn btn-success btn-xs ml-xs" data-modal="#updateModal" data-load-html="true" data-fetch-url="<?=URLROOT . "/boards/edit_html/$board->board_id"?>" href="#updateModal"> <i class=" fa fa-edit"></i>
                </a>
                <a class="btn btn-danger btn-xs ml-xs" href="<?=route("boards/delete/$board->board_id")?>"> <i class=" fa fa-trash"></i>
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


  <!-- Create New Board -->
  <div id="createModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
      <form action="<?= URLROOT ?>/boards/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
        <header class="panel-heading">
          <h4 class="panel-title">Create New Board</h4>
        </header>

        <div class="panel-body">
          <div class="row">
            <div class="col-md-12 mx-auto">
              <div class="form-group">
                <label class="control-label">Board Name <span class="required" aria-required="true">*</span></label>
                <input type="text" class="form-control" name="board_name" id="board_name" required="" title="Must Be Required" aria-required="true">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <div class="form-group mt-md mb-sm">
                <label class="control-label">Status <span class="required">*</span></label>
                <div class="radio-custom radio-inline ml-md mt-sm">
                  <input type="radio" id="board_status" name="board_status" value="1" checked>
                  <label for="radioExample1">Active</label>
                </div>
                <div class="radio-custom radio-inline">
                  <input type="radio" id="board_status" name="board_status" value="0">
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
              <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Add Board</button>
            </div>
          </div>
        </footer>

      </form>
    </section>
  </div>

  <!-- Update Board -->
  <div id="updateModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
    </section>
  </div>


</section>

