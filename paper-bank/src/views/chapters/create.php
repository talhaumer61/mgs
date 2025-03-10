<section role="main" class="content-body">
  <header class="page-header">
    <h2> Chapters Panel </h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <section class="panel panel-featured panel-featured-primary">
            <form action="<?= URLROOT ?>/chapters/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
              <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-plus-square"></i> Add New Chapter</h4>
              </div>

              <div class="panel-body">

                <div class="col-md-3">
                  <label class="control-label">Class
                    <span class="required">*</span></label>
                  <select class="form-control" required title="Must Be Required" data-plugin-selectTwo  onchange="get_class_subjects(this.value)"  data-width="100%" name="id_class" required title="Must Be Required">
                    <option value="">Select</option>
                    <?php foreach ($data['classes'] as $class) : ?>
                      <option value=<?= $class['class_id'] ?>><?= $class['class_name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="col-md-3">
                  <label class="control-label">Subject
                    <span class="required">*</span></label>
                  <select class="form-control" name="id_subject"  onchange="get_subject_chapters(this.value)"  data-plugin-selectTwo id="class_subjects" data-width="100%" >
                    <option value="-1">Select Cass First</option>
                  </select>
                </div>

                <div class="col-md-3 mx-auto">
                  <div class="form-group">
                    <label class="control-label">Chapter No. <span class="required" aria-required="true">*</span></label>
                    <input type="number" class="form-control" name="chapter_no" id="chapter_no" required="" title="Must Be Required" aria-required="true">
                  </div>
                </div>

                <div class="col-md-3 mx-auto">
                  <div class="form-group">
                    <label class="control-label">Chapter Name <span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control" name="chapter_name" id="chapter_name" required="" title="Must Be Required" aria-required="true">
                  </div>
                </div>
                
              </div>

              
            </div>


              <footer class="panel-footer mt-sm ">
                <div class="row ">
                  <div class="col-md-12 text-center ">
                    <button type="submit" id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Chapter</button>
                  </div>
                </div>
              </footer>
            </form>
          </section>
        </div>
      </div>
    </div>
  </div>
</section>