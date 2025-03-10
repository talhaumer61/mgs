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
                        <form action="<?= URLROOT ?>/classes/update/<?= $data['id'] ?>" class="mb-lg validate"
                              enctype="multipart/form-data" method="post" accept-charset="utf-8"
                              novalidate="novalidate">
                            <div class="panel-heading">
                                <h4 class="panel-title"><i class="fa fa-plus-square"></i> Update Class</h4>
                            </div>

                            <div class="panel-body">
                                <div class="col-md-1"></div>
                                <div class="col-md-4 mx-auto">
                                    <div class="form-group">
                                        <label class="control-label">Class Name <span class="required"
                                                                                      aria-required="true">*</span></label>
                                        <input type="text" class="form-control" name="class_name" id="class_name"
                                               value="<?= $data['class_name'] ?>" required="" title="Must Be Required"
                                               aria-required="true">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Sections <span class="required"
                                                                                    aria-required="true">*</span></label>
                                        <select multiple class="form-control select2-hidden-accessible" required=""
                                                title="Must Be Required " data-plugin-selecttwo="" data-width="100% "
                                                data-minimum-results-for-search="Infinity " name="section_id[] "
                                                onchange="get_classsection(this.value) " aria-required="true"
                                                tabindex="-1" aria-hidden="true">
                                            <!--                                            --><?php //for ($i = 0; $i <= sizeof($data['sections']); $i++) : ?>
                                            <!--                                                <option value="-->
                                            <? //= $data['sections'][$i]['section_id'] ?><!--"-->
                                            <!--                                                    --><?php
                                            //                                                    echo $data['sections'][$i]['section_id'] == $data['classesSection'][$i]['section_id'] ? "selected" : "";
                                            //                                                    ?>
                                            <!--                                                >-->
                                            <? //= $data['sections'][$i]?['section_name'] ?><!--</option>-->
                                            <!--                                            --><?php //endfor; ?>
                                            <?php
                                            foreach ($data['sections'] as $section) :
                                                ?>
                                                <option value="<?= $section['section_id'] ?>"
                                                    <?php
                                                    echo $section['isSelected'] ? "selected" : "";
                                                    ?>
                                                ><?= $section['section_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-1"></div>

                            </div>


                            <footer class="panel-footer mt-sm ">
                                <div class="row ">
                                    <div class="col-md-12 text-right ">
                                        <button type="submit " id="submit_student " name="submit_student "
                                                class="mr-xs btn btn-primary ">Update
                                        </button>
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