<form action="<?= URLROOT ?>/subjects/update/<?=$data['id']?>" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
  <header class="panel-heading">
    <h4 class="panel-title">Update Subject</h4>
  </header>

  <div class="panel-body">
    <div class="row">
      <div class="col-md-12 mx-auto">
        <div class="form-group">
          <label class="control-label">Subject Name <span class="required" aria-required="true">*</span></label>
          <input type="text" class="form-control" name="subject_name" id="subject_name" required="" title="Must Be Required" aria-required="true" value="<?=$data['subject']->subject_name?>">
        </div>
      </div>
    </div>

    <div class="row mt-sm">
      <div class="col-md-12 mx-auto">
        <label class="control-label">Classes <span class="required" aria-required="true">*</span></label>
        <div class="form-group">
          <select class="form-control" required title="Must Be Required" data-plugin-selectTwo  data-width="100%" name="id_class" required title="Must Be Required">
            <option value="">Select Class</option>
            <?php foreach ($data['classes'] as $class) : ?>
              <option value=<?= $class->class_id ?> <?=isset($data['id_class']) && $data['id_class'] == $class->class_id ? "selected" : '';?>><?= $class->class_name ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div class="form-group mt-md mb-sm">
          <label class="control-label">Status <span class="required">*</span></label>
          <div class="radio-custom radio-inline ml-md mt-sm">
            <input type="radio" id="subject_status" name="subject_status" value="1" <?=$data['subject']->subject_status ? 'checked' : ''?>>
            <label for="radioExample1">Active</label>
          </div>
          <div class="radio-custom radio-inline">
            <input type="radio" id="subject_status" name="subject_status" value="0" <?=!$data['subject']->subject_status ? 'checked' : ''?>>
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
        <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Update Subject</button>
      </div>
    </div>
  </footer>

</form>