<div class="app-sidebar" data-background-color="white">
    <div class="sidebar-header">
        <div class="logo clearfix">
            <a href="/dashboard" class="logo-text" style="text-align: center;">
                <div class="">
                    <img src="{{ asset('favicon.ico') }}" alt="logo" class="main-logo mb-2 width-100" />
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
                <li class="nav-item {{ $segment1 === 'dashboard' ? 'active' : null }}"><a href="/dashboard"><i
                            class="icon-home"></i><span data-i18n="" class="menu-title">Dashboard</span></a>
                </li>
                <li class="nav-item {{ $segment1 === 'challenges' ? 'active' : null }}"><a href="/challenges"><i
                            class="ft-activity"></i><span data-i18n="" class="menu-title">All Challenges</span></a>
                </li>
                <li class="nav-item {{ $segment1 === 'amounts' ? 'active' : null }}"><a href="/amounts"><i
                            class="icon-wallet"></i><span data-i18n="" class="menu-title">Transactions</span></a>
                </li>
                <li class="nav-item {{ $segment1 === 'users' ? 'active' : null }}"><a href="/users"><i
                            class="icon-users"></i><span data-i18n="" class="menu-title">Users</span></a>
                </li>
                <li class="nav-item {{ $segment1 === 'categories' ? 'active' : null }}"><a href="/categories"><i
                            class="icon-grid"></i><span data-i18n="" class="menu-title">Category</span></a>
                </li>
                <li class="nav-item {{ $segment1 === 'notifications' ? 'active' : null }}"><a href="/notifications"><i
                            class="icon-bell"></i><span data-i18n="" class="menu-title">Notifications</span></a>
                </li>
                <li class="nav-item {{ $segment1 === 'message' ? 'active' : null }}"><a href="/message"><i
                            class="icon-speech"></i><span data-i18n="" class="menu-title">Custom Message</span></a>
                </li>
                <li class="nav-item {{ $segment1 === 'settings' ? 'active' : null }}"><a href="/settings"><i
                            class="icon-wrench"></i><span data-i18n="" class="menu-title">Settings</span></a>
                </li>

            </ul>
        </div>
    </div>
    <div class="sidebar-background"></div>
</div>
