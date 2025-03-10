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
            <form action="<?= URLROOT ?>/chapters/update/<?=$data['chapter_id']?>" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
              <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-plus-square"></i> Update Chapter</h4>
              </div>

              <div class="panel-body">

                <div class="col-md-4">
                  <label class="control-label">Class <span class="required">*</span></label>
                  <input class="form-control" type="text" disabled value="<?=$data['class']->class_name?>" >

                </div>

                <div class="col-md-4">
                  <label class="control-label">Subject
                    <span class="required">*</span></label>
                  <input class="form-control" type="text" disabled value="<?=$data['subject']->subject_name?>" >

                </div>

                <div class="col-md-4 mx-auto">
                  <div class="form-group">
                    <label class="control-label">Chapter Name <span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control" value="<?=$data['chapter']->chapter_name?>" name="chapter_name" id="chapter_name" required="" title="Must Be Required" aria-required="true">
                  </div>
                </div>
              </div>


              <footer class="panel-footer mt-sm ">
                <div class="row ">
                  <div class="col-md-12 text-right ">
                    <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Update Chapter</button>
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