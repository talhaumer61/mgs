<section role="main" class="content-body">
  <section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
      <a href="#modalClass" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> New Class </a>
      <h2 class="panel-title"><i class="fa fa-list"></i> Class List </h2>
    </header>
    <div class="row">
      <div class="col-md-12">
        <?php
        // $customData = [
        //   'primary_key' => "class_id",
        //   'table_header' => ["Name"],
        //   'controller' => "classes",
        //   "data" => $data['classes'],
        //   "show" => ['name']
        // ];
        component("table", $data);
        ?>

      </div>
    </div>
  </section>
</section>

<!-- Create New Class -->
<div id="modalClass" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
        <form action="<?= URLROOT ?>/classes/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
            <header class="panel-heading">
                <h4 class="panel-title">Create New Class</h4>
            </header>

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label class="control-label">Class Name <span class="required" aria-required="true">*</span></label>
                            <input type="text" class="form-control" name="class_name" id="class_name" required="" title="Must Be Required" aria-required="true">
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label class="control-label">Sections <span class="required" aria-required="true">*</span></label>
                            <select multiple class="form-control " required="" title="Must Be Required " data-plugin-selecttwo data-width="100% " name="section_id[] "  aria-required="true" tabindex="-1" aria-hidden="true">
                                <?php foreach ($data['sections'] as  $class) :  ?>
                                    <option value="<?= $class['section_id'] ?>"><?= $class['section_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                        <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Class</button>
                    </div>
                </div>
            </footer>
        </form>
    </section>
</div>

<!-- Update the record -->
<div id="updateModel" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
        <form action="<?= URLROOT ?>/classes/update" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
            <header class="panel-heading">
                <h4 class="panel-title">Create New Class</h4>
            </header>

            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label class="control-label">Class Name <span class="required" aria-required="true">*</span></label>
                            <input type="text" class="form-control" name="class_name" id="class_name" required="" title="Must Be Required" aria-required="true">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px">
                    <div class="col-md-12 ">
                        <div class="form-group">
                            <label class="control-label">Sections <span class="required" aria-required="true">*</span></label>
                            <select multiple class="form-control " required="" title="Must Be Required " data-plugin-selecttwo data-width="100% " name="section_id[] "  aria-required="true" tabindex="-1" aria-hidden="true">
                                <?php foreach ($data['sections'] as  $class) :  ?>
                                    <option value="<?= $class['section_id'] ?>"><?= $class['section_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>


            </div>

            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                        <button type="submit" id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Class</button>
                    </div>
                </div>
            </footer>
        </form>
    </section>
</div>