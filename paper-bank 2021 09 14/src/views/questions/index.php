<section role="main" class="content-body">
  <header class="page-header">
    <h2>Questions Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">

      <section class="panel panel-featured panel-featured-primary">
        <form action="<?= URLROOT ?>/questions/filter_questions" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
          <header class="panel-heading">
            <h2 class="panel-title"><i class="fa fa-list"></i> Filter Questions</h2>
          </header>
          <div class="panel-body">
            <div class="form-group mb-md">
              <div class="col-md-4">
                <label class="control-label">Class </label>
                    <select class="form-control" required title="Must Be Required" data-plugin-selectTwo  onchange="get_class_subjects(this.value)"  data-width="100%" name="id_class" required title="Must Be Required">
                      <option value="">Select</option>
                      <?php foreach ($data['classes'] as $class) : ?>
                        <option value=<?= $class['class_id'] ?> <?=isset($data['id_class']) && $data['id_class'] == $class['class_id'] ? "selected" : '';?>><?= $class['class_name'] ?></option>
                      <?php endforeach; ?>
                    </select>
              </div>

              <div class="col-md-4">
                <label class="control-label">Subject </label>
                <?php  if (isset($data['id_subject']) && $data['id_subject'] != null): ?>
                <select class="form-control" name="id_subject"  onchange="get_subject_chapters(this.value)"  data-plugin-selectTwo id="class_subjects" data-width="100%" >
                  <option value="">Select Cass First</option>
                  <?php  foreach ($data['class_subjects'] as $class_subjects) : ?>
                  <option value="<?=$class_subjects['subject_id']?>" <?=isset($data['id_subject']) && $data['id_subject'] == $class_subjects['subject_id'] ? 'selected' : "";?>><?=$class_subjects['subject_name']?></option>
                  <?php endforeach; ?>
                </select>
                <?php else: ?>
                  <select class="form-control" name="id_subject"  onchange="get_subject_chapters(this.value)"  data-plugin-selectTwo id="class_subjects" data-width="100%" >
                    <option value="-1">Select Class First</option>
                  </select>
                <?php endif;?>
              </div>

              <div class="col-md-4">
                <label class="control-label">Chapter </label>
                <?php  if (isset($data['id_chapter']) && $data['id_chapter'] != null): ?>
                  <select class="form-control" name="id_chapter"  data-plugin-selectTwo data-width="100%"  id="subject_chapters">
                    <option value="">Select Subject First</option>
                    <?php  foreach ($data['subject_chapters'] as $subject_chaptes) : ?>
                      <option value="<?=$subject_chaptes['chapter_id']?>" <?=isset($data['id_chapter']) && $data['id_chapter'] == $subject_chaptes['chapter_id'] ? 'selected' : "";?>><?=$subject_chaptes['chapter_name']?></option>
                    <?php endforeach; ?>
                  </select>
                <?php else: ?>
                  <select class="form-control" name="id_chapter"  data-plugin-selectTwo data-width="100%"  id="subject_chapters">
                    <option value="-1">Select Cass First</option>
                  </select>
                <?php endif;?>
              </div>

            </div>
            <div class="col-md-12 text-center">
              <button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Show Questions</button>
            </div>
          </div>
        </form>
      </section>

      <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
          <a href="<?= URLROOT ?>/questions/add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Question</a>
          <h2 class="panel-title"><i class="fa fa-list"></i> Questions List</h2>
        </header>

        <?php
        $customData = [
          'primary_key' => "question_id",
          'table_header' => ["Question", 'Type', "Total Marks", "Class",  "Subject", "Chapter","Status"],
          'controller' => "questions",
          'show' => ['question', 'type', 'marks', 'class_name', 'subject_name', 'chapter_name','question_status'],
          "data" => $data['questions']
        ];
        component("table", $customData);
        ?>

      </section>