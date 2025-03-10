<section role="main" class="content-body">
  <header class="page-header"><h2><?=$data['title']?></h2></header>

  <!-- INCLUDEING PAGE -->
  <div class="col-md-12">
    <section class="panel panel-featured panel-featured-primary">
      <form action="<?= URLROOT ?>/boards/update/<?= $data['id'] ?>" class="mb-lg validate" method="post" accept-charset="utf-8" novalidate="novalidate">
        <header class="panel-heading">
          <h2 class="panel-title"><i class="fa fa-list"></i>Edit Board Information</h2>
        </header>

        <div class="panel-body">
          <div class="col-md-6 mx-auto">
            <div class="form-group">
              <label class="control-label">Board Name <span class="required" aria-required="true">*</span></label>
              <input type="text" class="form-control" name="board_name" id="board_name" required="" title="Must Be Required" aria-required="true" value="<?= $data['board']['board_name'] ?>">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Status <span class="required">*</span></label>
              <div>
                <div class="radio-custom radio-inline mt-sm">
                  <input type="radio" id="board_status" name="board_status" <?=$data['board']->board_status ? 'checked' : ''?> value="1">
                  <label for="radioExample1">Active</label>
                </div>
                <div class="radio-custom radio-inline">
                  <input type="radio" id="board_status" name="board_status" <?=!$data['board']->board_status ? 'checked' : ''?> value="0">
                  <label for="radioExample2">Inactive</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <footer class="panel-footer">
          <div class="row">
            <div class="col-md-12 text-right">
              <a href="<?=route("boards")?>" class="btn btn-default modal-dismiss">Cancel</a>
              <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Update Board</button>
            </div>
          </div>
        </footer>

      </form>
    </section>
  </div>

</section>