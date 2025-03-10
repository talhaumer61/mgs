<section role="main" class="content-body">
  <header class="page-header">
    <h2>Teacher Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">

      <!-- <section class="panel panel-featured panel-featured-primary">
        <form action="<?= URLROOT ?>/students/get_students_by_class" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">
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
              <button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Show Students</button>
            </div>
          </div>
        </form>
      </section> -->

      <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
          <a href="<?= URLROOT ?>/teachers/add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Teachers</a>
          <h2 class="panel-title"><i class="fa fa-list"></i> Teachers List</h2>
        </header>

        <?php
        $customData = [
          'primary_key' => "teacher_id",
          'table_header' => ["Photo", "Teacher Name", "Email", "Salary", "Phone", "Cnic", "Status"],
          'controller' => "teachers",
          'show' => ['image', 'teacher_name', 'email', 'salary', 'phone', 'teacher_cnic', 'status'],
          "data" => $data['teachers']
        ];
        component("table", $customData);
        ?>

      </section>