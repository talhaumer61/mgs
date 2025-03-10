<section role="main" class="content-body">

  <!-- 
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
  </section> -->

  <section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
      <a href="#createModal" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> New Paper Category</a>
      <h2 class="panel-title"><i class="fa fa-list"></i> Category List </h2>
    </header>
    <div class="row">
      <div class="col-md-12">
        <?php
        $customData = [
          'primary_key' => "paper_type_id",
          'table_header' => ["Category Name"],
          'controller' => "papertypes",
          "data" => $data['paper_types'],
          "show" => ['paper_type'],
        ];
        component("table", $customData);
        ?>

      </div>
    </div>
  </section>
</section>



<!-- Create New Class -->
<div id="createModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
        <form action="<?= URLROOT ?>/papertypes/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
            <header class="panel-heading">
                <h4 class="panel-title">Create New Section</h4>
            </header>

            <div class="panel-body">
                <div class="col-md-12 mx-auto">
                    <div class="form-group">
                        <label class="control-label">Category Name <span class="required" aria-required="true">*</span></label>
                        <input type="text" class="form-control" name="paper_type" id="paper_type" required="" title="Must Be Required" aria-required="true">
                    </div>
                </div>
            </div>

            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                        <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Paper Type</button>
                    </div>
                </div>
            </footer>
        </form>
    </section>
</div>
