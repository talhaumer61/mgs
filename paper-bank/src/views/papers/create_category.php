<section role="main" class="content-body">
  <header class="page-header">
    <h2> Student Panel</h2>
  </header>
  <!-- INCLUDEING PAGE -->
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-12">
          <section class="panel panel-featured panel-featured-primary">
            <form action="<?= URLROOT ?>/papertypes/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
              <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-plus-square"></i> Add New Paper Category</h4>
              </div>

              <div class="panel-body">
                <div class="col-md-3"></div>
                <div class="col-md-6 mx-auto">
                  <div class="form-group">
                    <label class="control-label">Category Name <span class="required" aria-required="true">*</span></label>
                    <input type="text" class="form-control" name="paper_type" id="paper_type" required="" title="Must Be Required" aria-required="true">
                  </div>
                </div>
                <div class="col-md-3"></div>

              </div>


              <footer class="panel-footer mt-sm ">
                <div class="row ">
                  <div class="col-md-12 text-right ">
                    <button type="submit " id="submit_paper_type" name="submit_paper_type" class="mr-xs btn btn-primary">Add Category</button>
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