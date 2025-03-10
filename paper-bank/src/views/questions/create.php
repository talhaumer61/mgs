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

          <form id="question_form" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
          <div class="panel-body">

            <!--===================================-->
            <!-- New Row -->
            <!--===================================-->
            <div class="row">

              <input type="hidden" name="question_id" id="question_id" value="0">
              <input type="hidden" name="id_question_type" value="<?=$_GET['question_type_id']?>">

              <div class="col-md-3">
                <label class="control-label">Board <span class="required">*</span></label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="id_board" >
                  <option value="">Select</option>
                  <?php foreach ($data['boards'] as $board) : ?>
                    <option value="<?= $board->board_id ?>"><?= $board->board_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Publisher <span class="required">*</span></label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="id_publisher" >
                  <option value="">Select</option>
                  <?php foreach ($data['publishers'] as $publisher) : ?>
                    <option value="<?= $publisher->publisher_id ?>"><?= $publisher->publisher_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Class <span class="required">*</span></label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" onchange="get_class_subjects(this.value)"  name="id_class" >
                  <option value="">Select</option>
                  <?php foreach ($data['classes'] as $class) : ?>
                    <option value=<?= $class->class_id?>><?= $class->class_name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Subject <span class="required">*</span></label>
                <select class="form-control" id="class_subjects" onchange="get_subject_chapters(this.value)" required title="Must Be Required" data-plugin-selectTwo data-width="100%" name="id_subject" >
                  <option value="">Select Class First</option>
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
                  <option value="">Select Subject First</option>
                </select>
              </div>

              <div class="col-md-3">
                <label class="control-label">Topics <span class="required">*</span></label>
                <select class="form-control" id="chapter_topics" required title="Must Be Required" data-plugin-selectTwo data-width="100%"  name="id_topic" >
                  <option value="">Select Chapter First</option>
                </select>
              </div>

              

              <div class="col-md-3">
                <label class="control-label">Page # <span class="required">*</span></label>
                <input type="number" placeholder="Enter Page number" class="form-control" min="1" name="page_num" id="page_num">
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
                <button type="submit " id="save_and_next" name="save_and_next" class="mr-xs btn btn-primary ">
                  Save and Next
                </button>
              </div>
            </div>
          </footer>
        </form>
      </section>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <section class="panel panel-featured panel-featured-primary">
        <div class="panel-heading">
          <h4 class="panel-title"><i class="fa fa-plus-square"></i>&nbsp;Questions List</h4>
        </div>

        <div class="panel-body">
          <div class="row mt-sm">
            <div class="col-sm-12">
              <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover text-center">
                  <thead>
                  <?php
                    if($layout=='mcqs')
                    {
                      ?>
                      <tr>
                        <th class="center" width="20px" >Sr#</th>
                        <th class="center">Question</th>
                        <th class="center" width="140px" >(A)</th>
                        <th class="center" width="140px">(B)</th>
                        <th class="center" width="140px">(c)</th>
                        <th class="center" width="140px">(D)</th>
                        <th class="center" width="140px">Correct</th>
                        <th class="center" width="90px">Action</th>
                      </tr>

                    <?php
                    }
                    else{
                    ?>
                      <tr>
                        <th class="center" width="20px">Sr#</th>
                        <th class="center" width="20px">Page#</th>
                        <th class="center">Questions</th>
                        <th width="90px;" class="center">Action</th>
                      </tr>
                    <?php
                    }
                    ?>
                  
                  </thead>
                  <tbody id="table_question">

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </section>
    </div>
  </div>

</section>



<div id="updateModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
  <section class="panel panel-featured panel-featured-primary">
  </section>
</div>