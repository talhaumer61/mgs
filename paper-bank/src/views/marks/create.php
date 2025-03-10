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
            <h2 class="panel-title"><i class="fa fa-list"></i>Select Paper</h2>
          </header>
          <div class="panel-body">
            <div class="form-group mb-md">
              <div class="col-md-4 col-md-offset-4">
                <label class="control-label">Paper
                  <span class="required">*</span></label>
                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" required title="Must Be Required">
                  <option value=""><?= sizeof($data['papers']) > 0 ? "Select" : "No paper found" ?></option>
                  <?php foreach ($data['papers'] as $paper) : ?>
                    <option value=<?= $paper->paper_id ?>><?= $paper->class->name . " " . $paper->subject->name ?></option>
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


      <!-- <?php
            if (isset($data['show']) && !empty($data['show'])) :
              echo "<td>$i</td>";
              $i++;
              foreach ($data['show'] as $key) : ?>
                      <td>
                        <?php
                        if ($key == "image") :
                          echo "<img src=" . URLROOT . '/uploads/' . $data1[$key] . " style='width:40px; height:40px;'>";
                        elseif ($key == "status") :
                          echo $data1[$key] ? "<div class='badge bg-success'>Active</div>" :  "<div class='badge bg-danger'>In Active</div>";
                        else :
                          echo $data1[$key];
                        endif;
                        ?>
                      </td>
                  <?php
                endforeach;
              endif;
                  ?>
                  <?php if (isset($data['show']) && !empty($data['show'])) : ?>
                    <td class="center ">
                      <a data-tippy="Enter Student Marks" class="btn btn-success btn-xs ml-xs " href="<?= URLROOT ?>/studentmarks/addmarks/<?= $data1->student_id ?>/<?= $data1->class_id ?>"><i class=" fa fa-edit"></i>
                      </a>
                    </td>
                  <?php endif; ?> -->