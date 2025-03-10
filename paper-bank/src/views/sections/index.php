<section role="main" class="content-body">
  <section class="panel panel-featured panel-featured-primary">
    <header class="panel-heading">
      <a href="#createModal" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> New Section</a>
      <h2 class="panel-title"><i class="fa fa-list"></i> Section List </h2>
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
<div id="createModal" class="zoom-anim-dialog modal-block modal-block mfp-hide">
    <section class="panel panel-featured panel-featured-primary">
        <form action="<?= URLROOT ?>/sections/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
            <header class="panel-heading">
                <h4 class="panel-title">Create New Section</h4>
            </header>

            <div class="panel-body">
                <div class="col-md-12 mx-auto">
                    <div class="form-group">
                        <label class="control-label">Section Name <span class="required" aria-required="true">*</span></label>
                        <input type="text" class="form-control" name="section_name" id="section_name" required="" title="Must Be Required" aria-required="true">
                    </div>
                </div>
            </div>

            <footer class="panel-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                        <button type="submit " id="submit_student " name="submit_student " class="mr-xs btn btn-primary ">Add Section</button>
                    </div>
                </div>
            </footer>
        </form>
    </section>
</div>
