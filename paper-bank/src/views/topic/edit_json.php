<form action="<?= URLROOT ?>/topics/update/<?=$data['id']?>" class="mb-lg validate" method="post" accept-charset="utf-8" novalidate="novalidate">
  <header class="panel-heading">
    <h4 class="panel-title">Update New Topic</h4>
  </header>

  <div class="panel-body">

    <div class="row">
      <div class="col-md-12 mx-auto">
        <div class="form-group">
          <label class="control-label">Topic Name <span class="required" aria-required="true">*</span></label>
          <input type="text" class="form-control" name="topic_name" id="topic_name" value="<?=$data['topic']->topic_name?>" required="" title="Must Be Required" aria-required="true">
        </div>
      </div>
    </div>

    <div class="row mt-sm">
      <div class="col-md-12 mx-auto">
        <div class="form-group">
          <label class="control-label">Class </label>
          <select class="form-control" required title="Must Be Required" data-plugin-selectTwo  onchange="get_class_subjects(this.value)"  data-width="100%" name="id_class" required title="Must Be Required">
            <option value="">Select</option>
            <?php foreach ($data['classes'] as $class) : ?>
              <option value=<?= $class->class_id ?> <?=isset($data['id_class']) && $data['id_class'] == $class->class_id ? "selected" : '';?>><?= $class->class_name ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <div class="row mt-sm">
      <div class="col-md-12 mx-auto">
        <div class="form-group">
          <label class="control-label">Subject </label>
          <select class="form-control" name="id_subject"  onchange="get_subject_chapters(this.value)"  data-plugin-selectTwo id="class_subjects" data-width="100%" >
            <option value="">Select Cass First</option>
            <?php  foreach ($data['topic_subjects'] as $topic_subject) : ?>
              <option value="<?=$topic_subject->subject_id?>" <?=isset($data['id_subject']) && $data['id_subject'] == $topic_subject->subject_id ? 'selected' : "";?>><?=$topic_subject->subject_name?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>

    <div class="row mt-sm">
      <div class="col-md-12 mx-auto">
        <div class="form-group">
          <label class="control-label">Chapter </label>
          <select class="form-control" name="id_chapter" data-plugin-selectTwo id="subject_chapters" data-width="100%" >
            <?php  foreach ($data['topic_chapters'] as $topic_chapter) : ?>
              <option value="<?=$topic_chapter->chapter_id?>" <?=isset($data['id_chapter']) && $data['id_chapter'] == $topic_chapter->chapter_id ? 'selected' : "";?>><?=$topic_chapter->chapter_name?></option>
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
            <input type="radio" id="topic_status" name="topic_status" <?=$data['topic']->topic_status ? 'checked' : ''?> value="1" checked>
            <label for="radioExample1">Active</label>
          </div>
          <div class="radio-custom radio-inline">
            <input type="radio" id="topic_status" name="topic_status" <?=!$data['topic']->topic_status ? 'checked' : ''?> value="0">
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
        <button type="submit" id="submit" name="submit" class="mr-xs btn btn-primary ">Update Topic</button>
      </div>
    </div>
  </footer>
</form>