<div class="login-container ">
    <div class="col-md-6">
        <section class="panel panel-featured panel-featured-primary">
            <form action="<?= URLROOT ?>/user/login" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-plus-square"></i> Login</h4>
                </div>

                <div class="panel-body" style="padding: 20px 40px">

                    <div class="row">
                        <div class="form-group">
                            <label class="control-label">Username <span class="required" aria-required="true">*</span></label>
                            <input type="text" class="form-control" name="username" id="username" required="" title="Must Be Required" autofocus="" aria-required="true">
                        </div>
                    </div>

                    <div class="row" style="margin-top: 20px">
                        <div class="form-group">
                            <label class="control-label">Password <span class="required" aria-required="true">*</span></label>
                            <input type="password" class="form-control" name="password" id="password" required="" title="Must Be Required" aria-required="true">
                        </div>
                    </div>
                </div>


                <footer class="panel-footer mt-sm ">
                    <div class="row ">
                        <div class="col-md-12 text-center ">
                            <button type="submit " id="submit_student " name="submit_student" class="mr-xs btn btn-primary" style="padding: 10px 30px">Login
                            </button>
                        </div>
                    </div>
                </footer>


            </form>
        </section>
    </div>

</div>