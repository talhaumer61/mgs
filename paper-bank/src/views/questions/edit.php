<section role="main" class="content-body">
  <header class="page-header">
    <h2>Questions Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary" >
          <div class="panel-heading">
            <h4 class="panel-title"><i class="fa fa-plus-square"></i>&nbsp;&nbsp<?=$data['title']?></h4>
          </div>

          <form  class="mb-lg validate" action="<?= URLROOT ?>/questions/update/<?=$data['question_id']?>" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
          <div class="panel-body">

            <!--===================================-->
            <!-- New Row -->
            <!--===================================-->
            <div class="row">

              <input type="hidden" name="question_id" value="<?=$data['question_id']?>">
              <input type="hidden" name="id_question_type" value="<?=$data['question']['id_question_type']?>">

              <div class="col-md-3">
                <label class="control-label">Board <span class="required">*</span></label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="id_board" >
                  <option value="">Select</option>
                  <?php foreach ($data['boards'] as $board) : ?>
                    <option value="<?= $board->board_id ?>" <?=isset($data['question']['id_board']) && $data['question']['id_board'] == $board->board_id ? "selected" : '';?>><?= $board->board_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Publisher <span class="required">*</span></label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="id_publisher" >
                  <option value="">Select</option>
                  <?php foreach ($data['publishers'] as $publisher) : ?>
                    <option value="<?= $publisher->publisher_id ?>" <?=isset($data['question']['id_publisher']) && $data['question']['id_publisher'] == $publisher->publisher_id ? "selected" : '';?>><?= $publisher->publisher_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Class <span class="required">*</span></label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" onchange="get_class_subjects(this.value)"  name="id_class" >
                  <option value="">Select</option>
                  <?php foreach ($data['classes'] as $class) : ?>
                    <option value=<?= $class->class_id?> <?=isset($data['question']['id_class']) && $data['question']['id_class'] == $class->class_id ? "selected" : '';?>><?= $class->class_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Subject <span class="required">*</span></label>
                <select class="form-control" id="class_subjects" onchange="get_subject_chapters(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_subject" >
                  <option value="">Select</option>
                  <?php foreach ($data['subjects'] as $subject) : ?>
                    <option value=<?= $subject->subject_id?> <?=isset($data['question']['id_subject']) && $data['question']['id_subject'] == $subject->subject_id ? "selected" : '';?>><?= $subject->subject_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>

            <!--===================================-->
            <!-- New Row -->
            <!--===================================-->
            <div class="row mt-sm">
              <div class="col-md-3">
                <label class="control-label">Chapter <span class="required">*</span></label>
                <select class="form-control" onchange="get_chapter_topics(this)" id="subject_chapters" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="id_chapter" >
                  <option value="">Select</option>
                  <?php foreach ($data['chapters'] as $chapter) : ?>
                    <option value=<?= $chapter->chapter_id?> <?=isset($data['question']['id_chapter']) && $data['question']['id_chapter'] == $chapter->chapter_id ? "selected" : '';?>><?= $chapter->chapter_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Topics <span class="required">*</span></label>
                <select class="form-control" id="chapter_topics" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="id_topic" >
                  <option value="">Select</option>
                  <?php foreach ($data['topics'] as $topic) : ?>
                    <option value=<?= $topic->topic_id?> <?=isset($data['question']['id_topic']) && $data['question']['id_topic'] == $topic->topic_id ? "selected" : '';?>><?= $topic->topic_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Page # <span class="required">*</span></label>
                <input type="number" value="<?=$data['question']['page_num']?>" class="form-control" min="1" name="page_num" id="page_num">
              </div>

            </div>

            <!--===================================-->
            <!-- New Row -->
            <!--===================================-->
            <?php
              $layout = $data['layout'];
              require_once "layout/$layout.php";
            ?>

          </div>

          <footer class="panel-footer">
            <div class="row">
              <div class="col-md-12 text-right" id="buttons">
                <a href="<?=route("questions")?>" class="mr-xs btn btn-info">Close</a>
                <button type="submit" name="update_question" class="mr-xs btn btn-primary ">
                  Update Question
                </button>
              </div>
            </div>
          </footer>
        </form>
      </section>
    </div>
  </div>

</section>
