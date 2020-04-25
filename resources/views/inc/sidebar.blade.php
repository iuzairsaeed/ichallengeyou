<div class="app-sidebar" data-background-color="white">
    <div class="sidebar-header">
        <div class="logo clearfix">
            <a href="/dashboard" class="logo-text" style="text-align: center;">
                <div class="">
                <img src="{{ asset('favicon.ico') }}" alt="logo" class="main-logo mb-2 width-100"/>
                </div>
            </a>
            <a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none">
                <i class="ft-x-circle"></i>
            </a>
        </div>
    </div>
    <div class="sidebar-content">
        <div class="nav-container">
            @php
                $segment1 = Request::segment(1);
                $segment2 = Request::segment(2);
            @endphp
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
                <li class="nav-item {{ $segment1 === 'dashboard' ? 'active' : null }}"><a href="/dashboard"><i class="icon-home"></i><span data-i18n="" class="menu-title">Dashboard</span></a>
                </li>
                <li class="has-sub nav-item {{ $segment1 === 'challenges' ? 'open' : null }}"><a href="#"><i class="ft-activity"></i><span data-i18n="" class="menu-title">Challenges</span></a>
                    <ul class="menu-content" >
                        <li class="nav-item {{ $segment1 === 'challenges' && $segment2 === null ? 'active' : null }}">
                            <a href="/challenges" class="menu-item">All Challenges</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
