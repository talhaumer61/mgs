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
                        <form action="<?= URLROOT ?>/students/create" class="mb-lg validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
                            <div class="panel-heading">
                                <h4 class="panel-title"><i class="fa fa-plus-square"></i> Add Student</h4>
                            </div>

                            <div class="panel-body">
                                <label class="control-label">Photo</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 130px; height: 130px;" data-trigger="fileinput">
                                                <img src="uploads/default-student.jpg" alt="...">
                                            </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 130px">

                                            </div>
                                            <div>
                                                <span class="btn btn-xs btn-default btn-file">
                                                    <span class="fileinput-new">Select image</span>
                                                    <span class="fileinput-exists">Change</span>
                                                    <input type="file" name="std_photo" accept="image/*">
                                                </span>
                                                <a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-sm">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Form Number </label>
                                            <input type="text" class="form-control" name="form_no" id="form_no" title="Must Be Required" autofocus="" onchange="get_formno(this.value)">
                                        </div>
                                    </div>
                                </div>

                                <div id="getadmissiondetail">
                                    <div class="row mt-sm">

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Student Name <span class="required" aria-required="true">*</span></label>
                                                <input type="text" class="form-control" name="std_name" id="std_name" required="" title="Must Be Required" autofocus="" aria-required="true">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Father Name <span class="required" aria-required="true">*</span></label>
                                                <input type="text" class="form-control" name="std_fathername" id="std_fathername" required="" title="Must Be Required" aria-required="true">
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Gender <span class="required" aria-required="true">*</span></label>
                                                <select name="std_gender" data-plugin-selecttwo="" data-width="100%" class="form-control populate select2-hidden-accessible" required="" title="Must Be Required" aria-required="true" tabindex="-1" aria-hidden="true">
                                                    <option value="">Select</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-sm">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Class <span class="required" aria-required="true">*</span></label>
                                                <select name="id_class" data-plugin-selecttwo="" onchange="get_class_sections(this.value)"  data-width="100%" class="form-control populate select2-hidden-accessible" required="" title="Must Be Required" aria-required="true" tabindex="-1" aria-hidden="true">
                                                    <?php $classes = $data['classes']; ?>
                                                    <option value="-1">Select</option>
                                                    <?php foreach ($classes as $class): ?>
                                                        <option value="<?=$class->class_id?>"><?=$class->name?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">Section <span class="required" aria-required="true">*</span></label>
                                                <select name="id_section" data-plugin-selecttwo="" id="class_sections"  data-width="100%" class="form-control populate select2-hidden-accessible" required="" title="Must Be Required" aria-required="true" tabindex="-1" aria-hidden="true">
                                                    <option value="-1">Select Class First</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-sm">

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label class="control-label">NIC / B-Form</label>
                                                <input type="text" class="form-control" name="std_nic" id="std_nic" "="">
                                            </div>
                                        </div>

                                        <div class=" col-sm-4 ">
                                            <div class=" form-group ">
                                                <label class=" control-label ">Phone <span class=" required "
                                                                                           aria-required=" true">*</span></label>
                                                <input type="text " class="form-control " name="std_phone" id="std_phone" required="" title="Must Be Required " aria-required="true">
                                            </div>
                                        </div>

                                        <div class="col-sm-4 ">
                                            <div class="form-group ">
                                                <label class="control-label ">Date of Birth </label>
                                                <input type="text " class="form-control " name="std_dob" id="std_dob " data-plugin-datepicker="">
                                            </div>
                                        </div>
                                    </div>


                                    <!-- <div class="row mt-sm">
                                        <div class="col-sm-4 ">
                                            <div class="form-group ">
                                                <label class="control-label ">Blood Group </label>
                                                <select class="form-control select2-hidden-accessible" data-plugin-selecttwo="" data-width="100% " data-minimum-results-for-search="Infinity " name="std_bloodgroup " tabindex="-1" aria-hidden="true">
                                                    <option value=" ">Select</option>
                                                    <option value="A+">A+</option>
                                                    <option value="A-">A-</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B-">B-</option>
                                                    <option value="O+">O+</option>
                                                    <option value="O-">O-</option>
                                                    <option value="AB+">AB+</option>
                                                    <option value="AB-">AB-</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4 ">
                                            <label class="control-label ">Class <span class="required " aria-required="true">*</span></label>
                                            <select class="form-control select2-hidden-accessible" required="" title="Must Be Required " data-plugin-selecttwo="" data-width="100% " data-minimum-results-for-search="Infinity" id="id_class" name="id_class" onchange="get_class_sections(this.value)" id="id_class" aria-required="true" tabindex="-1" aria-hidden="true">
                                                <option value="">Select a class</option>
                                                <?php foreach ($data['classes'] as $class) : ?>
                                                    <option value="<?= $class['class_id'] ?>"><?= $class['name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>


                                        <div class="col-sm-4 ">
                                            <div class="form-group ">
                                                <label class="control-label ">Section </label>
                                                <select id="class_sections" class="form-control select2-hidden-accessible" data-plugin-selecttwo="" data-width="100% " data-minimum-results-for-search="Infinity " name="id_section" tabindex="-1" aria-hidden="true">
                                                    <?php foreach ($data['sections'] as $section) : ?>
                                                        <option value="<?= $section['section_id'] ?>"><?= $section['section_name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>



                                    </div> -->

                                    <!-- <div class="col-sm-3 ">
                                          <div class="form-group ">
                                            <label class="control-label ">Religion </label>
                                            <select class="form-control select2-hidden-accessible" data-plugin-selecttwo="" data-width="100% " data-minimum-results-for-search="Infinity " name="std_religion " tabindex="-1" aria-hidden="true">
                                              <option value=" ">Select</option>
                                              <option value="Islam ">Islam</option>
                                              <option value="Christan ">Christan</option>
                                              <option value="Hindu ">Hindu</option>
                                              <option value="Sikeh ">Sikeh</option>
                                              <option value="Any other ">Any other</option>
                                            </select>
                                          </div>
                                        </div> -->
                                    <!-- </div> -->

                                    <div class="row mt-sm ">
                                        <div class="col-sm-4 ">
                                            <div class="form-group ">
                                                <label class="control-label ">Roll No.</label>
                                                <input type="text " class="form-control " name="std_rollno" id="std_rollno">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 ">
                                            <div class="form-group ">
                                                <label class="control-label ">Admission Date <span class="required " aria-required="true">*</span></label>
                                                <input type="text " class="form-control" name="std_admissiondate" id="std_admissiondate" data-plugin-datepicker="" required="" title="Must Be Required " aria-required="true">
                                            </div>
                                        </div>
                                        <div class="col-sm-4 ">
                                            <div class="form-group ">
                                                <label class="control-label ">City</label>
                                                <input type="text " class="form-control " name="std_city" id="std_city">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-sm ">
                                        <div class="col-sm-12 ">
                                            <div class="form-group ">
                                                <label class="control-label ">Address </label>
                                                <textarea type="text " class="form-control " name="std_address" id="std_address"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            <footer class="panel-footer mt-sm ">
                                <div class="row ">
                                    <div class="col-md-12 text-right ">
                                        <button type="submit " id="submit_student " name="submit_student" class="mr-xs btn btn-primary ">Add Student
                                        </button>
                                        <button type="reset " class="btn btn-default ">Reset</button>
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