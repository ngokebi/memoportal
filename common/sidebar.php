<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="home.php" class="logo logo-light">
        <span class="logo-lg">
            <img src="assets/memo.png" alt="logo">
        </span>
        <span class="logo-sm">
            <img src="assets/page.png" alt="small logo">
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="home.php" class="logo logo-dark">
        <span class="logo-lg">
            <img src="assets/memo.png" alt="dark logo">
        </span>
        <span class="logo-sm">
            <img src="assets/page.png" alt="small logo">
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!-- Leftbar User -->
        <div class="leftbar-user">
            <a href="pages-profile.html">
                <img src="assets/default.jpeg" alt="user-image" height="42" class="rounded-circle shadow-sm">
                <span class="leftbar-user-name mt-2"><?php echo $_SESSION['username'];?></span>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title">Navigation</li>

            <li class="side-nav-item">
                <a href="home.php" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Dashboard </span>
                </a>

            </li>

            <li class="side-nav-title">Apps</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTasks" aria-expanded="false" aria-controls="sidebarTasks" class="side-nav-link">
                    <i class="uil-clipboard-alt"></i>
                    <span> Memo </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTasks">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="memo.php">Formats</a>
                        </li>
                        <li>
                            <a href="memo_list.php">Memo List</a>
                        </li>
                    </ul>
                </div>
            </li>

            
            <li class="side-nav-item">
                <a href="memo_approval.php" class="side-nav-link">
                    <i class="ri-file-mark-line"></i>
                    <span> Approve Memo </span>
                </a>
            </li>


        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>