<form action="<?= URLROOT ?>/classes/update/<?=$data['id']?>" class="mb-lg validate" method="post" accept-charset="utf-8" novalidate="novalidate">
  <header class="panel-heading">
    <h4 class="panel-title">Update New Class</h4>
  </header>

  <div class="panel-body">
    <div class="row">
      <div class="col-md-12 mx-auto">
        <div class="form-group">
          <label class="control-label">Class Name <span class="required" aria-required="true">*</span></label>
          <input type="text" class="form-control" name="class_name" id="class_name" value="<?=$data['class']->class_name?>" required="" title="Must Be Required" aria-required="true">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="form-group mt-md mb-sm">
          <label class="control-label">Status <span class="required">*</span></label>
          <div class="radio-custom radio-inline ml-md mt-sm">
            <input type="radio" id="class_status" name="class_status" <?=$data['class']->class_status ? 'checked' : ''?> value="1" checked>
            <label for="radioExample1">Active</label>
          </div>
          <div class="radio-custom radio-inline">
            <input type="radio" id="class_status" name="class_status" <?=!$data['class']->class_status ? 'checked' : ''?> value="0">
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
        <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Update Class</button>
      </div>
    </div>
  </footer>
</form>