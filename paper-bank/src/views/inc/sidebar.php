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
            <a href="<?= route("dashboard")?>">
              <i class="fa fa-tachometer"></i>
              <span>Dashboard</span>
            </a>
          </li>

          <!-- Questions START -->
          <li class="nav-parent  ">
            <a href="<?= route("questions")?>">
              <i class="fa fa-question"></i><span>Questions</span>
            </a>
          </li>
          <!-- Questions END -->

          <!-- Paper Style START -->
          <!-- <li class="nav-parent  ">
            <a href="<?= route("paperstyles")?>">
              <i class="fa fa-check-circle"></i><span>Paper Style</span>
            </a>
          </li> -->
          <!-- Paper Style END -->

          <!-- Generate Paper START -->
          <li class="nav-parent  ">
            <a href="<?= route("papers")?>">
              <i class="fa fa-print"></i><span>Generate Paper</span>
            </a>
          </li>
          <!-- Generate Paper END -->

          
          <!-- Chapters Start -->
          <li class="nav-parent  ">
            <a href="<?= route("chapters")?>">
              <i class="fa fa-book"></i><span>Chapters</span>
            </a>
          </li>
          <!-- Chapters  End -->

          <!-- Boards Start -->
          <li class="nav-parent  ">
            <a href="<?=route("boards")?>">
              <i class="fa fa-university"></i><span>Boards</span>
            </a>
          </li>
          <!-- Boards  End -->


          <!-- Subject Start -->
          <li class="nav-parent  ">
            <a href="<?=route("topics")?>">
              <i class="fa fa-folder-open"></i><span>Topics</span>
            </a>
          </li>
          <!-- Subject  End -->

          <!-- Question Type Start -->
          <!-- <li class="nav-parent  ">
            <a href="<?=route("questiontype")?>">
              <i class="fa fa-check-square"></i><span>Question Types</span>
            </a>
          </li> -->
          <!-- Question Type  End -->

          <!-- Publisher Start -->
          <li class="nav-parent  ">
            <a href="<?=route("publishers")?>">
              <i class="fa fa-file-text"></i><span>Publishers</span>
            </a>
          </li>
          <!-- Publisher End -->


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