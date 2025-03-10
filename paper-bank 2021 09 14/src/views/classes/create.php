<section role="main" class="content-body">
  <header class="page-header">
    <h2> Student Panel </h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <section class="panel panel-featured panel-featured-primary">
            <form action="<?= URLROOT ?>/classes/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
              <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-plus-square"></i> Add New Class</h4>
              </div>

              <div class="panel-body">
                <div class="col-md-1"></div>
                <div class="col-md-4 mx-auto">
                  <div class="form-group">
                    <label class="control-label">Class Name <span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control" name="class_name" id="class_name" required="" title="Must Be Required" aria-required="true">
                  </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="control-label">Sections <span class="required" aria-required="true">*</span></label>
                        <select multiple class="form-control " required="" title="Must Be Required " data-plugin-selecttwo data-width="100% " name="section_id[] "  aria-required="true" tabindex="-1" aria-hidden="true">
                            <?php foreach ($data['sections'] as  $class) :  ?>
                                <option value="<?= $class['section_id'] ?>"><?= $class['section_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-1"></div>

              </div>


              <footer class="panel-footer mt-sm ">
                <div class="row ">
                  <div class="col-md-12 text-right ">
                    <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Class</button>
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