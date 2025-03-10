<form action="<?= URLROOT ?>/questiontype/update/<?=$data['id']?>" class="mb-lg validate" method="post" accept-charset="utf-8" novalidate="novalidate">
  <header class="panel-heading">
    <h4 class="panel-title">Update Exam Type</h4>
  </header>

  <div class="panel-body">
    <div class="row">
      <div class="col-md-12 mx-auto">
        <div class="form-group">
          <label class="control-label">Exam Type Name <span class="required" aria-required="true">*</span></label>
          <input type="text" disabled class="form-control" name="question_type_name" id="question_type_name" value="<?=$data['question_type']->question_type_name?>" required="" title="Must Be Required" aria-required="true">
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="form-group mt-md mb-sm">
          <label class="control-label">Status <span class="required">*</span></label>
          <div class="radio-custom radio-inline ml-md mt-sm">
            <input type="radio" id="question_type_status" name="question_type_status" <?=$data['question_type']->question_type_status ? 'checked' : ''?> value="1" checked>
            <label for="radioExample1">Active</label>
          </div>
          <div class="radio-custom radio-inline">
            <input type="radio" id="question_type_status" name="question_type_status" <?=!$data['question_type']->question_type_status ? 'checked' : ''?> value="0">
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
        <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Update Question Type</button>
      </div>
    </div>
  </footer>
</form>