<section role="main" class="content-body">


  <section class="panel panel-featured panel-featured-primary">
    <form action="<?= URLROOT ?>/subjects/get_subjects_by_class" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
      <header class="panel-heading">
        <h2 class="panel-title"><i class="fa fa-list"></i> Select Class</h2>
      </header>
      <div class="panel-body">
        <div class="form-group mb-md">
          <div class="col-md-4 col-md-offset-4">
            <label class="control-label">Class
              <span class="required">*</span></label>
            <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" required title="Must Be Required">
              <option value="">Select</option>
              <?php foreach ($data['classes'] as $class) : ?>
                <option value=<?= $class['class_id'] ?>><?= $class['name'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-12 text-center">
          <button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Show Subjects</button>
        </div>
      </div>
    </form>
  </section>

  <section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
      <a href="<?=URLROOT?>/subjects/add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> New Subject</a>
      <h2 class="panel-title"><i class="fa fa-list"></i> Subject List </h2>
    </header>
    <div class="row">
      <div class="col-md-12">
        <?php
        $customData = [
          'primary_key' => "subject_id",
          'table_header' => ["Subject Name", "Class Name"],
          'controller' => "subjects",
          "data" => $data['subjects'],
          "show" => ['name', 'class_name'],
        ];
        component("table", $customData);
        ?>

      </div>
    </div>
  </section>
</section>



<!-- Create New Class -->
<div id="createModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary" style="min-width: 900px">
        <form action="<?= URLROOT ?>/subjects/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
            <header class="panel-heading">
                <h4 class="panel-title">Create New Section</h4>
            </header>

            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Subject Name<span class="required" aria-required="true">*</span></label>
                            <input type="text" class="form-control" name="subject_name" id="subject_name" required="" title="Must Be Required" aria-required="true">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">Class <span class="required" aria-required="true">*</span></label>
                            <select class="form-control select2-hidden-accessible" required="" title="Must Be Required " data-plugin-selecttwo="" data-width="100% " data-minimum-results-for-search="Infinity " name="class_id" aria-required="true" tabindex="-1" aria-hidden="true">
                                <?php foreach ($data['classes'] as  $class) :  ?>
                                    <option value="<?= $class['class_id'] ?>"><?= $class['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <?php foreach ($data['paper_types'] as  $paper_type) :  ?>
                <div class="row" style="margin-top: 20px">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Paper Type <span class="required" aria-required="true">*</span></label>
                            <input type="hidden" name="paper_type[]"  value="<?= $paper_type['paper_type_id'] ?>">
                            <input type="text" class="form-control" value="<?= $paper_type['paper_type'] ?>" disabled >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">No. of Objective Questions<span class="required" aria-required="true">*</span></label>
                            <input class="form-control" name="max_numbers[]" type="number" class="form-control" min="1">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">No. of Subjective Questions<span class="required" aria-required="true">*</span></label>
                            <input class="form-control" name="max_numbers[]" type="number" class="form-control" min="1">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Total Marks<span class="required" aria-required="true">*</span></label>
                            <input class="form-control" name="max_numbers[]" type="number" class="form-control" min="1">
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                        <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Subject</button>
                    </div>
                </div>
            </footer>
        </form>
    </section>
</div>
