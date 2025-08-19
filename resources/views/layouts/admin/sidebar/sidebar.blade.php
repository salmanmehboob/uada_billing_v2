<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu">
    <div class="app-brand demo">
        <a href="/" class="app-brand-link">
              <span class="app-brand-logo demo">
                <span class="text-primary">
                 <img src="{{ asset('assets/img/favicon/logo.png') }}" width="32" height="32" alt="App Logo">

                </span>
              </span>
            <span class="app-brand-text demo menu-text fw-bold ms-3">{{ config('app.name', 'AppFlex POS') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
            <i class="icon-base ti tabler-x d-block d-xl-none"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->

        <li class="menu-item {{ request()->is('home') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon icon-base ti tabler-dashboard"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/bills*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon icon-base ti tabler-invoice"></i>
                <div data-i18n="Bill">Bill</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/bills') ? 'active' : '' }}">
                    <a href="{{ route('admin.bills.index') }}" class="menu-link">
                        <i class="menu-icon icon-base ti tabler-invoice"></i>
                        <div data-i18n="Bill System">Bill System</div>
                    </a>
                </li>
{{--                <li class="menu-item {{ request()->is('admin/bills-combine*') ? 'active' : '' }}">--}}
{{--                    <a href="{{ route('admin.bills.combine.index') }}" class="menu-link">--}}
{{--                        <i class="menu-icon icon-base ti tabler-file-invoice"></i>--}}
{{--                        <div data-i18n="Combine Bill Generation">Combine Bill Generation</div>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="menu-item {{ request()->fullUrlIs(route('admin.bills.combine.index', ['is_generated_combine' => 1])) ? 'active' : '' }}">
                    <a href="{{ route('admin.bills.combine.index', ['is_generated_combine' => 1]) }}" class="menu-link">
                        <i class="menu-icon icon-base ti tabler-file-invoice"></i>
                        <div data-i18n="Combine Bills">Combine Bills (List/Print)</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->fullUrlIs(route('admin.bills.combine.list', ['is_generated_combine' => 1])) ? 'active' : '' }}">
                    <a href="{{ route('admin.bills.combine.list', ['is_generated_combine' => 1]) }}" class="menu-link">
                        <i class="menu-icon icon-base ti tabler-file-invoice"></i>
                        <div data-i18n="Combine (List/Print)">Combine Bills (List/Print)</div>
                    </a>
                </li>


            </ul>
        </li>

        <li class="menu-item {{ request()->is('admin/dropdown*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="Dropdown">Dropdown</div>
            </a>
            <ul class="menu-sub">


                <li class="menu-item {{ request()->is('admin/dropdown/sectors') ? 'active' : '' }}">
                    <a href="{{ route('admin.dropdown.sectors.index') }}" class="menu-link">
                        <div data-i18n="Sector">Sector</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('admin/dropdown/charges') ? 'active' : '' }}">
                    <a href="{{ route('admin.dropdown.charges.index') }}" class="menu-link">
                        <div data-i18n="Charges">Charges</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('admin/dropdown/sizes') ? 'active' : '' }}">
                    <a href="{{ route('admin.dropdown.sizes.index') }}" class="menu-link">
                        <div data-i18n="Plot Sizes">Plot Sizes</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('admin/dropdown/types') ? 'active' : '' }}">
                    <a href="{{ route('admin.dropdown.types.index') }}" class="menu-link">
                        <div data-i18n="Plot Types">Plot Types</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('admin/dropdown/banks') ? 'active' : '' }}">
                    <a href="{{ route('admin.dropdown.banks.index') }}" class="menu-link">
                        <div data-i18n="Banks">Banks</div>
                    </a>
                </li>


            </ul>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-users"></i>
                <div data-i18n="Users">Users</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/allotees') ? 'active' : '' }}">
                    <a href="{{ route('admin.allotees.index') }}" class="menu-link">
                        <div data-i18n="Allotees">Allotees</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->is('admin/users') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="menu-link">
                        <div data-i18n="User">User</div>
                    </a>
                </li>

            </ul>
        </li>

        <li class="menu-item {{ request()->is('admin/settings*') ? 'open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-settings"></i>
                <div data-i18n="Settings">Settings</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/settings') ? 'active' : '' }}">
                    <a href="{{ route('admin.settings') }}" class="menu-link">
                        <i class="menu-icon icon-base ti tabler-settings"></i>
                        <div data-i18n="General Setting">General Setting</div>
                    </a>
                </li>

            </ul>
        </li>



    </ul>


</aside>

<div class="menu-mobile-toggler d-xl-none rounded-1">
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large text-bg-secondary p-2 rounded-1">
        <i class="ti tabler-menu icon-base"></i>
        <i class="ti tabler-chevron-right icon-base"></i>
    </a>
</div>
<!-- / Menu -->
