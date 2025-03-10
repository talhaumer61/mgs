<section role="main" class="content-body">
  <?php

  use Illuminate\Support\Facades\URL;

  $student = $data['student']; ?>
  <!-- INCLUDEING PAGE -->
  <div class="row appear-animation fadeInRight appear-animation-visible" data-appear-animation="fadeInRight" data-appear-animation-delay="100" style="animation-delay: 100ms;">
    <div class="col-md-4">
      <section class="panel">
        <div class="panel-body">
          <div class="thumb-info mb-md"><img src="<?= URLROOT ?>/uploads/<?= $student['image'] ?>" class="rounded img-responsive">
            <div class="thumb-info-title">
              <span class="thumb-info-inner"><?= $student['student_name'] ?></span>
              <?php if ($student['status']) : ?>
                <span><span class="label label-success">Active</span></span>
              <?php else : ?>

                <span><span class="label label-danger">In Active </span></span>
              <?php endif; ?>

            </div>
          </div>
          <div class="widget-toggle-expand mb-xs">
            <div class="widget-content-expanded">
              <table class="table table-striped table-condensed mb-none">
                <tbody>
                  <tr>
                    <td>Student Name</td>
                    <td align="right"><?= $student['student_name'] ?></td>
                  </tr>
                  <tr>
                    <td>Father Name</td>
                    <td align="right"><?= $student['guardian_name'] ?></td>
                  </tr>
                  <tr>
                    <td>Roll No</td>
                    <td align="right"><?= $student['roll_num'] ?></td>
                  </tr>
                  <!-- <tr>
                    <td>Registration Number</td>
                    <td align="right">BRAND-2021-2</td>
                  </tr> -->
                  <tr>
                    <td>Class</td>
                    <td align="right"><?= $data['class']['name'] ?></td>
                  </tr>
                  <tr>
                    <td>Section</td>
                    <td align="right"><?= $data['section']['section_name'] ?></td>
                  </tr>
                  <tr>
                    <td>Phone</td>
                    <td align="right"><?= $student['phone'] ?></td>
                  </tr>
                  <!-- <tr>
                    <td>Whatsapp</td>
                    <td align="right"></td>
                  </tr> -->
                  <tr>
                    <td>Gender</td>
                    <td align="right"><?= $student['gender'] == 1 ? "Male" : "Female" ?></td>
                  </tr>
                  <!-- <tr>
                    <td>Blood Group</td>
                    <td align="right"></td>
                  </tr> -->
                  <tr>
                    <td>Birthday</td>
                    <td align="right"><?= $student['dob'] ?></td>
                  </tr>
                  <tr>
                    <td>NIC</td>
                    <td align="right"><?= $student['guardian_cnic'] ?></td>
                  </tr>
                  <!-- <tr>
                    <td>Religion</td>
                    <td align="right"></td>
                  </tr> -->
                  <tr>
                    <td>Admission Date</td>
                    <td align="right"><?= $student['admission_date'] ?></td>
                  </tr>
                  <!-- <tr>
                    <td>Guardian</td>
                    <td align="right"></td>
                  </tr> -->
                  <tr>
                    <td>City</td>
                    <td align="right"><?= $student['city'] ?></td>
                  </tr>
                  <tr>
                    <td>Address</td>
                    <td align="right"><?= $student['address'] ?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
    <div class="col-md-8">
      <div class="tabs tabs-primary">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#edit" data-toggle="tab"><i class="fa fa-user"></i> <span class="hidden-xs"> Profile</span></a>
          </li>
          <li>
            <a href="#resetpass" data-toggle="tab"><i class="fa fa-lock"></i> <span class="hidden-xs"> Change Password</span></a>
          </li>
        </ul>
        <div class="tab-content">
          <div id="edit" class="tab-pane active">
            <form action="<?= URLROOT ?>/students/update/<?= $data['id'] ?>" class="form-horizontal validate" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate="novalidate">
              <input type="hidden" name="std_id" id="std_id" value="10">
              <fieldset class="mt-lg">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Photo</label>
                  <div class="col-md-8">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                      <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput"><img src="<?= URLROOT ?>/uploads/<?= $student['image'] ?>" class="rounded img-responsive">
                      </div>
                      <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                      <div>
                        <span class="mr-xs btn btn-xs btn-default btn-file">
                          <span class="fileinput-new">Select image</span>
                          <span class="fileinput-exists">Change</span>
                          <input type="file" name="std_photo" accept="image/*">
                        </span>
                        <a href="#" class="btn btn-xs btn-warning fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Student Name <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" required="" name="std_name" id="std_name" value="<?= $student['student_name'] ?>" aria-required="true">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Father Name <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" required="" name="std_fathername" id="std_fathername" value="<?= $student['guardian_name'] ?>" aria-required="true">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Roll No</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="std_rollno" id="std_rollno" value="<?= $student['roll_num'] ?>">
                  </div>
                </div>

                <!-- 
                <div class="form-group">
                  <label class="col-md-3 control-label">Group</label>
                  <div class="col-md-8">
                    <select class="form-control select2-hidden-accessible" data-plugin-selecttwo="" data-width="100%" data-minimum-results-for-search="Infinity" name="id_group" tabindex="-1" aria-hidden="true">
                      <option value="">Select</option>
                      <option value="5">Higher Secondary</option>
                      <option value="3">Middle</option>
                      <option value="1">Pre-Primary</option>
                      <option value="2">Primary</option>
                      <option value="4">Secondary </option>
                    </select><span class="select2 select2-container select2-container--bootstrap" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-id_group-d3-container"><span class="select2-selection__rendered" id="select2-id_group-d3-container" title="Select">Select</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                  </div>
                </div>

                 -->
                <div class="form-group">
                  <label class="col-md-3 control-label">Class <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-8">
                    <select class="form-control select2-hidden-accessible" required="" title="Must Be Required" data-plugin-selecttwo="" data-width="100%" data-minimum-results-for-search="Infinity" name="id_class" onchange="get_class_sections(this.value)" aria-required="true" tabindex="-1" aria-hidden="true">
                      <option value="">Select</option>
                      <?php foreach ($data['classes'] as $class) : ?>
                        <option value="<?= $class['class_id'] ?>" <?= $data['class']['class_id'] == $class['class_id'] ? 'selected' : ''; ?>>
                          <?= $class['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div id="geteditclasssection">
                  <div class="form-group mb-lg">
                    <label class="col-md-3 control-label">Section</label>
                    <div class="col-md-8">
                      <select class="form-control select2-hidden-accessible" data-plugin-selecttwo="" data-width="100%" data-minimum-results-for-search="Infinity" id="class_sections" name="id_section" tabindex="-1" aria-hidden="true">
                        <option value="">Select</option>
                        <?php foreach ($data['sections'] as $section) : ?>
                          <option value="<?= $section['section_id'] ?>" <?= $data['section']['section_id'] == $section['section_id'] ? 'selected' : ''; ?>>
                            <?= $section['section_name'] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Phone <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="std_phone" value="<?= $student['phone'] ?>" data-plugin-options="{ &quot;startView&quot;: 2 }">
                  </div>
                </div>
                <!-- <div class="form-group">
                  <label class="col-sm-3 control-label">Whatsapp</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="std_whatsapp" value="" data-plugin-options="{ &quot;startView&quot;: 2 }">
                  </div>
                </div> -->

                <div class="form-group">
                  <label class="col-sm-3 control-label">Gender <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-8">
                    <select name="std_gender" data-plugin-selecttwo="" data-minimum-results-for-search="Infinity" data-width="100%" class="form-control populate select2-hidden-accessible" required="" title="Must Be Required" aria-required="true" tabindex="-1" aria-hidden="true">
                      <option value="">Select</option>
                      <option value="male" <?= $student['gender'] == 1 ? 'selected' : ''; ?>>Male</option>
                      <option value="female" <?= $student['gender'] == 0 ? 'selected' : ''; ?>>Female</option>
                    </select>
                  </div>
                </div>


                <div class="form-group">
                  <label class="col-sm-3 control-label">Status <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-8">
                    <select name="std_status" data-plugin-selecttwo="" data-minimum-results-for-search="Infinity" data-width="100%" class="form-control populate select2-hidden-accessible" required="" title="Must Be Required" aria-required="true" tabindex="-1" aria-hidden="true">
                      <option value="1" <?= $student['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                      <option value="0" <?= $student['status'] == 0 ? 'selected' : ''; ?>>InActive</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Date of Birth</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="std_dob" value="<?= $student['dob'] ?>" data-plugin-datepicker="">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">NIC / B-Form <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" required="" name="std_nic" value="<?= $student['guardian_cnic'] ?>" aria-required="true">
                  </div>
                </div>


                <div class="form-group">
                  <label class="col-sm-3 control-label">Admission Date <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" required="" name="std_admissiondate" value="<?= $student['admission_date'] ?>" data-plugin-datepicker="" aria-required="true">
                  </div>
                </div>


                <!-- <div class="form-group">
                  <label class="col-md-3 control-label">Guardian</label>
                  <div class="col-md-8">
                    <select class="form-control select2-hidden-accessible" data-plugin-selecttwo="" data-width="100%" data-minimum-results-for-search="Infinity" name="id_guardian" tabindex="-1" aria-hidden="true">
                      <option value="">Select</option>
                      <option value="1">Father</option>
                      <option value="2">Mother</option>
                      <option value="3">Brother</option>
                      <option value="4">Sister</option>
                      <option value="5">Uncle</option>
                      <option value="6">Other</option>
                    </select><span class="select2 select2-container select2-container--bootstrap" dir="ltr" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-id_guardian-9f-container"><span class="select2-selection__rendered" id="select2-id_guardian-9f-container" title="Select">Select</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                  </div>
                </div> -->


                <div class="form-group">
                  <label class="col-sm-3 control-label">City</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="std_city" value="<?= $student['city'] ?>">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Address</label>
                  <div class="col-md-8">
                    <textarea type="text" class="form-control" name="std_address"><?= $student['address'] ?></textarea>
                  </div>
                </div>
                <!-- 

                <div class="form-group">
                  <label class="col-sm-3 control-label">Status <span class="required" aria-required="true">*</span></label>
                  <div class="col-md-9">
                    <div class="radio-custom radio-inline">
                      <input type="radio" id="std_status" name="std_status" value="1">
                      <label for="radioExample1">Active</label>
                    </div>
                    <div class="radio-custom radio-inline">
                      <input type="radio" id="std_status" name="std_status" value="2" checked="">
                      <label for="radioExample1">Left</label>
                    </div>
                    <div class="radio-custom radio-inline">
                      <input type="radio" id="std_status" name="std_status" value="3">
                      <label for="radioExample1">Expel</label>
                    </div>
                    <div class="radio-custom radio-inline">
                      <input type="radio" id="std_status" name="std_status" value="4">
                      <label for="radioExample1">Freeze</label>
                    </div>
                    <div class="radio-custom radio-inline">
                      <input type="radio" id="std_status" name="std_status" value="5">
                      <label for="radioExample1">Passed</label>
                    </div>
                  </div>
                </div> -->


              </fieldset>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-offset-3 col-sm-5">
                    <button type="submit" name="changes_student" id="changes_student" class="btn btn-primary">Update Profile</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div id="bank_details" class="tab-pane ">
            <section class="panel panel-pvs-shadow mt-lg">
              <header class="panel-heading panel-featured-primary pvs-heading-tran">
                <div class="pull-right">
                  <a href="#add_account" class="modal-with-move-anim btn btn-xs btn-primary">
                    <i class="fa fa-plus-square"></i> Add Account
                  </a>
                </div>
                <h2 class="panel-title">List Of Bank Details</h2>
              </header>
              <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-bordered table-condensed table-striped mb-none">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Bank Name</th>
                        <th>Account Name</th>
                        <th>Branch</th>
                        <th>Employee</th>
                        <th>IFSC Code</th>
                        <th>Account TYpe</th>
                        <th>Account No</th>
                        <th>Status </th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </section>
          </div>

          <div id="resetpass" class="tab-pane">
            <form action="<?= URLROOT ?>/mgs/students/update/<?= $data['id'] ?>" class="form-horizontal validate" method="post" accept-charset="utf-8" novalidate="novalidate">
              <fieldset class="mt-lg">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Current Password <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" required="" title="Must Be Required" name="password" value="" aria-required="true">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">New Password <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" required="" title="Must Be Required" name="new_password" value="" aria-required="true">
                  </div>
                </div>
                <div class="form-group mb-md">
                  <label class="col-sm-3 control-label">Confirm New Password <span class="required" aria-required="true">*</span></label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" required="" title="Must Be Required" name="confirm_new_password" value="" aria-required="true">
                  </div>
                </div>
              </fieldset>
              <div class="panel-footer">
                <div class="row">
                  <div class="col-sm-offset-3 col-sm-5">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>