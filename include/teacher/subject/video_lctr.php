<?php
echo '
<header class="panel-heading bg-primary">
    <p class="text-weight-semibold mt-none text-center" style="font-size: 24px; color:#ffffff;">Video Lectures  - '.$value_detail['class_name'].' ('.$value_detail['section_name'].')</p>
</header>
<header class="panel-heading">
    <a href="#make_vlecture" class="modal-with-move-anim btn btn-primary btn-xs pull-right"><i class="fa fa-plus-square"></i> Make Announcement</a>
	<h2 class="panel-title"><i class="fa fa-list"></i> Video Lectures List</h2>
</header>
<div class="panel-body">';
    include_once('video_lctr/query_vid_lecture.php');
    include_once('video_lctr/list.php');
    include_once("include/modals/teacher_video_lectures/modal_add.php");
    echo'
</div>';