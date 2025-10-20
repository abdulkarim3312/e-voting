<div class="sidenav-menu">

    <!-- Brand Logo -->
    <a href="/" class="logo">
        <span class="logo-light">
            <span class="logo-lg"><img src="{{ asset('/logo.webp') }}" alt="logo">
                <b>Sovle IT</b>
            </span>
            <span class="logo-sm"><img src="{{ asset('/logo.webp') }}" alt="small logo"></span>
        </span>

        <span class="logo-dark">
            <span class="logo-lg"><img src="{{ asset('/logo.webp') }}" alt="dark logo">
                <b>Sovle IT</b>
            </span>
            <span class="logo-sm"><img src="{{ asset('/logo.webp') }}" alt="small logo"></span>
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <button class="button-sm-hover">
        <i class="fa-duotone fa-light fa-circle-dot fa-beat-fade"></i>
    </button>

    <!-- Full Sidebar Menu Close Button -->
    <button class="button-close-fullsidebar">
        <i class="fas fa-times align-middle"></i>
    </button>

    <div data-simplebar class="menu-bar-scroller">

        <!--- Sidenav Menu -->
        <ul class="side-nav">
            <li class="side-nav-title">Dash</li>

            <li class="side-nav-item">
                <a href="/user/dashboard" class="side-nav-link">
                    <span class="menu-icon"><i class="fa-duotone fa-light fa-house"></i></span>
                    <span class="menu-text"> ড্যাশবোর্ড </span>
                    <span class="badge bg-success rounded-pill"></span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('user.voting') }}" class="side-nav-link">
                    <span class="menu-icon"><i class="fa-duotone fa-thin fa-person-booth"></i></span>
                    <span class="menu-text"> আপনার ভোট দিন </span>
                </a>
            </li>

            <li class="side-nav-item d-none">
                <a href="dashboard-clinic.html" class="side-nav-link">
                    <span class="menu-icon"><i class="fas fa-hospital"></i></span>
                    <span class="menu-text"> Clinic </span>
                </a>
            </li>



            <li class="side-nav-item d-none">
                <a data-bs-toggle="collapse" href="#sidebarLayouts" aria-expanded="false" aria-controls="sidebarLayouts" class="side-nav-link">
                    <span class="menu-icon"><i class="fas fa-th-large"></i></span>
                    <span class="menu-text"> Layouts </span>
                    <span class="menu-arrow">
                        <i class="fa-solid fa-angle-down"></i>
                    </span>
                </a>
                <div class="collapse" id="sidebarLayouts">
                    <ul class="sub-menu">                        
                        <li class="side-nav-item">
                            <a href="layouts-horizontal.html" target="_blank" class="side-nav-link">Horizontal</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-detached.html" target="_blank" class="side-nav-link">Detached</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-full.html" target="_blank" class="side-nav-link">Full View</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-fullscreen.html" target="_blank" class="side-nav-link">Fullscreen View</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-hover.html" target="_blank" class="side-nav-link">Hover Menu</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-compact.html" target="_blank" class="side-nav-link">Compact</a>
                        </li>
                        <li class="side-nav-item">
                            <a href="layouts-icon-view.html" target="_blank" class="side-nav-link">Icon View</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item  d-none">
                <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link">
                    <span class="menu-icon"><i class="fas fa-layer-group"></i></span>
                    <span class="menu-text"> Multi Level </span>
                    <span class="menu-arrow">
                        <i class="fa-solid fa-angle-down"></i>
                    </span>
                </a>
                <div class="collapse" id="sidebarMultiLevel">
                    <ul class="sub-menu">
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarSecondLevel" aria-expanded="false" aria-controls="sidebarSecondLevel" class="side-nav-link">
                                <span class="menu-text"> Second Level </span>
                                <span class="menu-arrow">
                                    <i class="fa-solid fa-angle-down"></i>
                                </span>
                            </a>
                            <div class="collapse" id="sidebarSecondLevel">
                                <ul class="sub-menu">
                                    <li class="side-nav-item">
                                        <a href="javascript: void(0);" class="side-nav-link">
                                            <span class="menu-text">Item 1</span>
                                        </a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a href="javascript: void(0);" class="side-nav-link">
                                            <span class="menu-text">Item 2</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarThirdLevel" aria-expanded="false" aria-controls="sidebarThirdLevel" class="side-nav-link">
                                <span class="menu-text"> Third Level </span>
                                <span class="menu-arrow">
                                    <i class="fa-solid fa-angle-down"></i>
                                </span>
                            </a>
                            <div class="collapse" id="sidebarThirdLevel">
                                <ul class="sub-menu">
                                    <li class="side-nav-item">
                                        <a href="javascript: void(0);" class="side-nav-link">Item 1</a>
                                    </li>
                                    <li class="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarFourthLevel" aria-expanded="false" aria-controls="sidebarFourthLevel" class="side-nav-link">
                                            <span class="menu-text"> Item 2 </span>
                                            <span class="menu-arrow">
                                                <i class="fa-solid fa-angle-down"></i>
                                            </span>
                                        </a>
                                        <div class="collapse" id="sidebarFourthLevel">
                                            <ul class="sub-menu">
                                                <li class="side-nav-item">
                                                    <a href="javascript: void(0);" class="side-nav-link">
                                                        <span class="menu-text">Item 2.1</span>
                                                    </a>
                                                </li>
                                                <li class="side-nav-item">
                                                    <a href="javascript: void(0);" class="side-nav-link">
                                                        <span class="menu-text">Item 2.2</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>