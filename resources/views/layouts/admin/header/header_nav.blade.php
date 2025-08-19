<!-- Navbar -->

<nav
    class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme no-print"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base ti tabler-menu-2 icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">


        <ul class="navbar-nav flex-row align-items-center ms-md-auto">




            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a
                    class="nav-link dropdown-toggle hide-arrow p-0"
                    href="javascript:void(0);"
                    data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('storage/' . \App\Helpers\GeneralHelper::getSettingValue('dept_logo') ) }}" alt="User" class="rounded-circle" />
                    </div>

                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item mt-0" href="/">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-2">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User Avatar" class="rounded-circle" />
                                    </div>

                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{auth()->user()->name}}</h6>
                                    <small class="text-body-secondary">{{auth()->user()->email}}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0)">
                            <i class="icon-base ti tabler-user me-3 icon-md"></i
                            ><span class="align-middle">My Profile</span>
                        </a>
                    </li>
{{--                    <li>--}}
{{--                        <a class="dropdown-item" href="{{route('admin.settings')}}">--}}
{{--                            <i class="icon-base ti tabler-settings me-3 icon-md"></i--}}
{{--                            ><span class="align-middle">Settings</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}

                    <li>
                        <div class="dropdown-divider my-1 mx-n2"></div>
                    </li>
                    <li>
                        <div class="d-grid px-2 pt-2 pb-1">
                            <a class="btn btn-sm btn-danger d-flex"  href="{{ route('logout') }}"  onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();" >
                                <small class="align-middle">Logout</small>
                                <i class="icon-base ti tabler-logout ms-2 icon-14px"></i>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                        </div>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

<!-- / Navbar -->
