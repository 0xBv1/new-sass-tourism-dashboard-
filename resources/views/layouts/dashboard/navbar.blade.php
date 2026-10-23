<!-- Page Header Start -->
<div class="page-main-header">
    <div class="main-header-right row">
        <div class="main-header-left d-lg-none w-auto">
            <div class="logo-wrapper">
                <a href="{{ route('dashboard') }}">
                    <img class="blur-up lazyloaded d-block d-lg-none"
                         src="{{ logo() }}" alt="">
                </a>
            </div>
        </div>
        <div class="mobile-sidebar w-auto">
            <div class="media-body text-end switch-sm">
                <label class="switch">
                    <a href="javascript:void(0)">
                        <i id="sidebar-toggle" data-feather="align-left"></i>
                    </a>
                </label>
            </div>
        </div>
        <div class="nav-right col">
            <ul class="nav-menus">
                <li>
                    <form class="form-inline search-form">
                        <div class="form-group">
                            <input class="form-control-plaintext" type="search" placeholder="Search..">
                            <span class="d-sm-none mobile-search">
                                        <i data-feather="search"></i>
                                    </span>
                        </div>
                    </form>
                </li>
                <li>
                    <a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()">
                        <i data-feather="maximize-2"></i>
                    </a>
                </li>
                <li>
                    <a data-toggle-theme-url="{{ route('dashboard.toggle-theme') }}"
                       class="text-dark btn-dark-setting-navbar{{ admin()->theme=='light'?'':' dark' }}"
                       href="javascript:void(0)">
                        <i data-feather="{{ admin()->theme == 'light'? 'moon' : 'sun' }}"></i>
                    </a>
                </li>

                <li title="Purge Cache">
                    <a class="text-dark clear-app-cache" href="{{ route('dashboard.cache.clear') }}">
                        <i data-feather="trash-2"></i>
                    </a>
                </li>

                <li>
                    <a title="Download Sitemap xml" href="{{ route('dashboard.sitemap.generate') }}">
                        <i data-feather="download"></i>
                    </a>
                </li>

                <x-dashboard.partials.notifications />


                <li class="onhover-dropdown">
                    <div class="media align-items-center">
                        <img class="align-self-center pull-right img-50 blur-up rounded-circle lazyloaded"
                             src="{{ asset('assets/admin/images/users/user-placeholder.png') }}" alt="header-user">
                        <div class="dotted-animation">
                            <span class="animate-circle"></span>
                            <span class="main-circle"></span>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div p-20 profile-dropdown-hover">
                        <li>
                            <a href="{{ route('profile.edit') }}">
                                <i data-feather="user"></i>Edit Profile
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}">
                                <i data-feather="log-out"></i>Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="d-lg-none mobile-toggle pull-right">
                <i data-feather="more-horizontal"></i>
            </div>
        </div>
    </div>
</div>
<!-- Page Header Ends -->
