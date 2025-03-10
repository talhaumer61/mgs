<?php

use Illuminate\Support\Facades\URL;
?>
<!-- start: sidebar -->
<aside id="sidebar-left" class="sidebar-left">
  <div class="sidebar-header">
    <div class="sidebar-title">
      Navigation
    </div>
    <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
      <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
    </div>
  </div>

  <div class="nano">
    <div class="nano-content">
      <nav id="menu" class="nav-main" role="navigation">
        <ul class="nav nav-main">

          <!-- DASHBOARD -->
          <li class=" ">
            <a href="<?= URLROOT ?>/dashboard ">
              <i class="fa fa-tachometer"></i>
              <span>Dashboard</span>
            </a>
          </li>

          <!-- Classes START -->
          <li class="nav-parent  ">
            <a href="<?= URLROOT ?>/questions">
              <i class="fa fa-question"></i><span>Questions</span>
            </a>
          </li>
          <!-- Classe END -->

          <!-- Classes START -->
          <li class="nav-parent  ">
            <a href="<?= URLROOT ?>/subjectmarks">
              <i class="fa fa-check-circle"></i><span>Paper Marks</span>
            </a>
          </li>
          <!-- Classe END -->


          <!-- Classes START -->
          <li class="nav-parent  ">
            <a href="<?= URLROOT ?>/papers">
              <i class="fa fa-print"></i><span>Generate Paper</span>
            </a>
          </li>
          <!-- Classe END -->
          <li class="nav-parent  ">
            <a href="<?= URLROOT ?>/chapters">
              <i class="fa fa-print"></i><span>Chapters</span>
            </a>
          </li>
          <!-- Chapters    -->
          `

          <!-- Classes START -->
<!--          <li class="nav-parent  ">-->
<!--            <a href="--><?//= URLROOT ?><!--/studentmarks">-->
<!--              <i class="fa fa-slideshare"></i><span>Enter Result</span>-->
<!--            </a>-->
<!--          </li>-->
          <!-- Classe END -->

          <!-- Classes START -->
<!--          <li class="nav-parent  ">-->
<!--            <a href="--><?//= URLROOT ?><!--/result">-->
<!--              <i class="fa fa-slideshare"></i><span>Result List</span>-->
<!--            </a>-->
<!--          </li>-->
          <!-- Classe END -->

          <!-- USER PROFILE END -->
        </ul>
      </nav>

    </div>

    <script>
      // Maintain Scroll Position
      if (typeof localStorage !== "undefined") {
        if (localStorage.getItem("sidebar-left-position") !== null) {
          var initialPosition = localStorage.getItem("sidebar-left-position"),
            sidebarLeft = document.querySelector("#sidebar-left .nano-content");
          sidebarLeft.scrollTop = initialPosition;
        }
      }
    </script>

  </div>
</aside>
<!-- end: sidebar -->