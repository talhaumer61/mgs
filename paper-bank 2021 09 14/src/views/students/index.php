<section role="main" class="content-body">
  <header class="page-header">
    <h2> Student Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">

      <section class="panel panel-featured panel-featured-primary">
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
      </section>

      <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
          <a href="<?= URLROOT ?>/students/add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Student</a><a href="studentcards_print.php" class="btn btn-primary btn-xs pull-right mr-sm"><i class="glyphicon glyphicon-print"></i> Print Cards</a>
          <h2 class="panel-title"><i class="fa fa-list"></i> Students List</h2>
        </header>

        <?php
        $customData = [
          'primary_key' => "student_id",
          'table_header' => ["Photo", "Student Name", "Father Name", "Roll No", "Class", "Phone", "Cnic", "Status"],
          'controller' => "students",
          'show' => ['image', 'student_name', 'guardian_name', 'roll_num', 'class_id', 'phone', 'guardian_cnic', 'status'],
          "data" => $data['students']
        ];
//        component("table", $customData);
          $data = $customData;
        ?>

        <div class="panel-body" style="width: 100%;">
          <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
            <thead>
            <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1">Sr #</th><th class="sorting_disabled" rowspan="1" colspan="1"> Photo</th><th class="sorting_disabled" rowspan="1" colspan="1"> Student Name</th><th class="sorting_disabled" rowspan="1" colspan="1"> Father Name</th><th class="sorting_disabled" rowspan="1" colspan="1"> Roll No</th><th class="sorting_disabled" rowspan="1" colspan="1"> Class</th><th class="sorting_disabled" rowspan="1" colspan="1"> Phone</th><th class="sorting_disabled" rowspan="1" colspan="1"> Cnic</th><th class="sorting_disabled" rowspan="1" colspan="1"> Status</th><th width="100px;" class="center sorting_disabled" rowspan="1" colspan="1">Options</th></tr>
            </thead>
            <tbody>

            <?php
              // Increment
              $i = 1;
              foreach ($data['data'] as $data1) :
                ?>
                <tr>
                  <td><?=$i?></td>
                  <td><img src="<?=URLROOT?>/uploads/<?=$data1->image?>" alt="" style='width:40px; height:40px;'></td>
                  <td><?=$data1->student_name?></td>
                  <td><?=$data1->guardian_name?></td>
                  <td><?=$data1->roll_num?></td>
                  <td><?=$data1->class->name?></td>
                  <td><?=$data1->phone?></td>
                  <td><?=$data1->guardian_cnic?></td>
                  <td>
                    <?php if($data1->status) : ?>
                      <div class='badge bg-success'>Active</div>
                    <?php else:?>
                      <div class='badge bg-success'>In Active</div>
                    <?php endif; ?>
                  </td>

                  <?php if (isset($data['show']) && !empty($data['show'])) : ?>
                    <td class=" center ">
                      <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT . '/' . $data['controller'] . '/edit' . '/' . $data1[$data['primary_key']] ?>"> <i class=" fa fa-edit"></i>
                      </a>
                      <a class="btn btn-danger btn-xs ml-xs" href="<?= URLROOT . '/' . $data['controller'] . '/delete' . '/' . $data1[$data['primary_key']] ?>"> <i class=" fa fa-trash"></i>
                      </a>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>


      </section>