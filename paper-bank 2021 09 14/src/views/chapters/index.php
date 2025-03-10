<section role="main" class="content-body">
  <header class="page-header">
    <h2>Chapters Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">

<!--      <section class="panel panel-featured panel-featured-primary">-->
<!--        <form action="--><?//= URLROOT ?><!--/chapters/filter_chapters" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8">-->
<!--          <header class="panel-heading">-->
<!--            <h2 class="panel-title"><i class="fa fa-list"></i> Filter Chapters</h2>-->
<!--          </header>-->
<!--          <div class="panel-body">-->
<!--            <div class="form-group mb-md">-->
<!--              <div class="col-md-4">-->
<!--                <label class="control-label">Class-->
<!--                  <span class="required">*</span></label>-->
<!--                <select class="form-control" required title="Must Be Required" data-plugin-selectTwo  onchange="get_class_subjects(this.value)"  data-width="100%" name="id_class" required title="Must Be Required">-->
<!--                  <option value="">Select</option>-->
<!--                  --><?php //foreach ($data['classes'] as $class) : ?>
<!--                    <option value=--><?//= $class['class_id'] ?><!--<?//= $class['class_name'] ?><!--</option>-->
<!--                  --><?php //endforeach; ?>
<!--                </select>-->
<!--              </div>-->
<!---->
<!--              <div class="col-md-4">-->
<!--                <label class="control-label">Subject-->
<!--                  <span class="required">*</span></label>-->
<!--                <select class="form-control" name="id_subject"  onchange="get_subject_chapters(this.value)"  data-plugin-selectTwo id="class_subjects" data-width="100%" >-->
<!--                  <option value="-1">Select Cass First</option>-->
<!--                </select>-->
<!--              </div>-->
<!---->
<!--              <div class="col-md-4">-->
<!--                <label class="control-label">Chapter-->
<!--                  <span class="required">*</span></label>-->
<!--                <select class="form-control" name="id_chapter"  data-plugin-selectTwo data-width="100%"  id="subject_chapters">-->
<!--                  <option value="">Select Subject First</option>-->
<!--                </select>-->
<!--              </div>-->
<!---->
<!--            </div>-->
<!--            <div class="col-md-12 text-center">-->
<!--              <button type="submit" id="show_students" name="show_students" class="mr-xs btn btn-primary">Show Chapters</button>-->
<!--            </div>-->
<!--          </div>-->
<!--        </form>-->
<!--      </section>-->

      <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
          <a href="<?= URLROOT ?>/chapters/add" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Chapter</a>
          <h2 class="panel-title"><i class="fa fa-list"></i> Chapters List</h2>
        </header>

        <div class="panel-body" style="width: 100%;">
          <table class="table table-bordered table_default table-striped table-condensed mb-none" id="table_export">
            <thead>
            <tr>
              <th class='text-center'>Sr #</th>
              <th>Chapter Name</th>
              <th>Subject</th>
              <th class="text-center">Options</th>
            </tr>
            </thead>
            <tbody>

            <?php
            // Increment
            $i = 1;
            foreach ($data['chapters'] as $chapter) :

              ?>
              <tr>
                <td class="text-center"><?=$i?></td>
                <td><?=$chapter->chapter_name?></td>
                <td><?=$chapter->subject->subject_name?></td>
                <td class=" center ">
                  <a class="btn btn-success btn-xs ml-xs" href="<?= URLROOT?>/chapters/edit/<?=$chapter->chapter_id?>"> <i class=" fa fa-edit"></i>
                  </a>
                  <a class="btn btn-danger btn-xs ml-xs" href="<?= URLROOT?>/chapters/delete/<?=$chapter->chapter_id?>"> <i class=" fa fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php
            $i++;
            endforeach; ?>
            </tbody>
          </table>

        </div>

      </section>