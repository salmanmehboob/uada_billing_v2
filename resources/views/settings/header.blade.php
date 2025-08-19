<ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}" href="{{route('admin.settings')}}"
        ><i class="icon-base ti tabler-users icon-sm me-1_5"></i> Setting</a
        >
    </li>

    <li class="nav-item">
        <a class="nav-link  {{ request()->routeIs('admin.settings.warehouses') ? 'active' : '' }}"
           href="{{route('admin.settings.warehouses.index')}}"
        ><i class="icon-base ti tabler-lock icon-sm me-1_5"></i> Warehouse</a
        >
    </li>

</ul>
