@php
$AUTH_USER = Auth::user();
@endphp

<nav id="sidebar" aria-label="Main Navigation">
    <div class="content-header bg-white-5">
        <a class="font-w600 text-dual" href="{{ url('/dashboard') }}">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide font-size-h5 tracking-wider">
                {{ env('APP_NAME', 'Sufra') }}
            </span>
        </a>

        <div>
            <div class="dropdown d-inline-block ml-2">
                <a class="btn btn-sm btn-dual" id="sidebar-themes-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="si si-drop"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right font-size-sm smini-hide border-0" aria-labelledby="sidebar-themes-dropdown">
                    @foreach(['default', 'amethyst', 'city', 'flat', 'modern', 'smooth'] as $theme)
                        <a class="dropdown-item d-flex align-items-center justify-content-between font-w500" href="{{ url('/users/' . $theme . '/set-theme') }}">
                            <span>{{ ucfirst($theme) }}</span>
                            <i class="fa fa-circle text-{{ $theme }}"></i>
                        </a>
                    @endforeach

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item font-w500" href="{{ url('/users/light/set-sidebar') }}">
                        <span>Sidebar Light</span>
                    </a>
                    <a class="dropdown-item font-w500" href="{{ url('/users/dark/set-sidebar') }}">
                        <span>Sidebar Dark</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item font-w500" href="{{ url('/users/light/set-header') }}">
                        <span>Header Light</span>
                    </a>
                    <a class="dropdown-item font-w500" href="{{ url('/users/dark/set-header') }}">
                        <span>Header Dark</span>
                    </a>
                </div>
            </div>

            <a class="d-lg-none btn btn-sm btn-dual ml-1" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
        </div>
    </div>

    <div class="js-sidebar-scroll">                
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                        <i class="nav-main-link-icon si si-layers"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                </li>
                
                @canany(['orders-listing', 'all'])
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('kitchen/dashboard') ? 'active' : '' }}" href="{{ url('/kitchen/dashboard') }}">
                        <i class="nav-main-link-icon si si-layers"></i>
                        <span class="nav-main-link-name">Kitchen</span>
                    </a>
                </li>

                <li class="nav-main-item {{ request()->is('orders/active-listing') || request()->is('orders/inactive-listing') || request()->is('orders*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->is('orders/active-listing') || request()->is('orders/inactive-listing') || request()->is('orders*') ? 'true' : 'false' }}" href="#">
                        <i class="nav-main-link-icon si si-social-dropbox"></i>
                        <span class="nav-main-link-name">Orders</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('orders/open-listing') ? 'active' : '' }}" href="{{ url('/orders/open-listing') }}">
                                <span class="nav-main-link-name">Open Orders</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('orders/completed-listing') ? 'active' : '' }}" href="{{ url('/orders/completed-listing') }}">
                                <span class="nav-main-link-name">Completed Orders</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('orders/declined-listing') ? 'active' : '' }}" href="{{ url('/orders/declined-listing') }}">
                                <span class="nav-main-link-name">Declined Orders</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('orders/cancelled-listing') ? 'active' : '' }}" href="{{ url('/orders/cancelled-listing') }}">
                                <span class="nav-main-link-name">Cancelled Orders</span>
                            </a>
                        </li>  
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('orders*') ? 'active' : '' }}" href="{{ url('/orders') }}">
                                <span class="nav-main-link-name">All Orders</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                @canany(['menus-listing', 'all'])
                <li class="nav-main-item {{ request()->is('menus/active-listing') || request()->is('menus/inactive-listing') || request()->is('menus*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->is('menus/active-listing') || request()->is('menus/inactive-listing') || request()->is('menus*') ? 'true' : 'false' }}" href="#">
                        <i class="nav-main-link-icon si si-social-dropbox"></i>
                        <span class="nav-main-link-name">Menus</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('menus/active-listing') ? 'active' : '' }}" href="{{ url('/menus/active-listing') }}">
                                <span class="nav-main-link-name">Active Menus</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('menus/inactive-listing') ? 'active' : '' }}" href="{{ url('/menus/inactive-listing') }}">
                                <span class="nav-main-link-name">InActive Menus</span>
                            </a>
                        </li>  
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('menus*') ? 'active' : '' }}" href="{{ url('/menus') }}">
                                <span class="nav-main-link-name">All Menus</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                @canany(['items-listing', 'all'])
                <li class="nav-main-item {{ request()->is('items/active-listing') || request()->is('items/inactive-listing') || request()->is('items*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->is('items/active-listing') || request()->is('items/inactive-listing') || request()->is('items*') ? 'true' : 'false' }}" href="#">
                        <i class="nav-main-link-icon si si-social-dropbox"></i>
                        <span class="nav-main-link-name">Items</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('items/active-listing') ? 'active' : '' }}" href="{{ url('/items/active-listing') }}">
                                <span class="nav-main-link-name">Active Items</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('items/inactive-listing') ? 'active' : '' }}" href="{{ url('/items/inactive-listing') }}">
                                <span class="nav-main-link-name">InActive Items</span>
                            </a>
                        </li>  
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('items*') ? 'active' : '' }}" href="{{ url('/items') }}">
                                <span class="nav-main-link-name">All Items</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                @canany(['serve-tables-listing', 'all'])
                <li class="nav-main-item {{ request()->is('serve-tables/active-listing') || request()->is('serve-tables/inactive-listing') || request()->is('serve-tables*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->is('serve-tables/active-listing') || request()->is('serve-tables/inactive-listing') || request()->is('serve-tables*') ? 'true' : 'false' }}" href="#">
                        <i class="nav-main-link-icon si si-social-dropbox"></i>
                        <span class="nav-main-link-name">Tables</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('serve-tables/active-listing') ? 'active' : '' }}" href="{{ url('/serve-tables/active-listing') }}">
                                <span class="nav-main-link-name">Active Tables</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('serve-tables/inactive-listing') ? 'active' : '' }}" href="{{ url('/serve-tables/inactive-listing') }}">
                                <span class="nav-main-link-name">InActive Tables</span>
                            </a>
                        </li>  
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('serve-tables*') ? 'active' : '' }}" href="{{ url('/serve-tables') }}">
                                <span class="nav-main-link-name">All Tables</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany
                
                @canany(['app-users-listing', 'all'])
                <li class="nav-main-item {{ request()->is('app-users/active-listing') || request()->is('app-users/inactive-listing') || request()->is('app-users*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->is('app-users/active-listing') || request()->is('app-users/inactive-listing') || request()->is('app-users*') ? 'true' : 'false' }}" href="#">
                        <i class="nav-main-link-icon si si-social-dropbox"></i>
                        <span class="nav-main-link-name">App Users</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('app-users/active-listing') ? 'active' : '' }}" href="{{ url('/app-users/active-listing') }}">
                                <span class="nav-main-link-name">Active App Users</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('app-users/inactive-listing') ? 'active' : '' }}" href="{{ url('/app-users/inactive-listing') }}">
                                <span class="nav-main-link-name">InActive App Users</span>
                            </a>
                        </li>  
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('app-users*') ? 'active' : '' }}" href="{{ url('/app-users') }}">
                                <span class="nav-main-link-name">All App Users</span>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcanany

                @canany(['users-listing', 'all'])
                <li class="nav-main-item {{ request()->is('users/active-listing') || request()->is('users/inactive-listing') || request()->is('users*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->is('users/active-listing') || request()->is('users/inactive-listing') || request()->is('users*') ? 'true' : 'false' }}" href="#">
                        <i class="nav-main-link-icon si si-users"></i>
                        <span class="nav-main-link-name">Users</span>
                    </a>
                    <ul class="nav-main-submenu">  
                        @can('users-listing')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('users/active-listing') ? 'active' : '' }}" href="{{ url('/users/active-listing') }}">
                                <span class="nav-main-link-name">Active Users</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('users/inactive-listing') ? 'active' : '' }}" href="{{ url('/users/inactive-listing') }}">
                                <span class="nav-main-link-name">InActive Users</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ url('/users') }}">
                                <span class="nav-main-link-name">All Users</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['roles-listing', 'general-settings-listing', 'addon-types-listing', 'all'])
                <li class="nav-main-item {{ request()->is('roles*') || request()->is('general-settings*') || request()->is('addon-types*') ? 'open' : '' }}">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="{{ request()->is('roles*') || request()->is('general-settings*') || request()->is('addon-types*') ? 'true' : 'false' }}" href="#">
                        <i class="nav-main-link-icon si si-social-dropbox"></i>
                        <span class="nav-main-link-name">Settings</span>
                    </a>
                    <ul class="nav-main-submenu">
                        @can('roles-listing')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('roles*') ? 'active' : '' }}" href="{{ url('/roles') }}">
                                <span class="nav-main-link-name">Roles</span>
                            </a>
                        </li>
                        @endcan  
                        @can('general-settings-listing')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('general-settings*') ? 'active' : '' }}" href="{{ url('/general-settings') }}">
                                <span class="nav-main-link-name">General</span>
                            </a>
                        </li>
                        @endcan
                        @can('addon-types-listing')
                        <li class="nav-main-item">
                            <a class="nav-main-link {{ request()->is('addon-types*') ? 'active' : '' }}" href="{{ url('/addon-types') }}">
                                <span class="nav-main-link-name">Addon Types</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['all'])
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ url('/kitchen/clear_pending_orders') }}">
                        <i class="nav-main-link-icon si si-layers"></i>
                        <span class="nav-main-link-name">Clear Pending Orders</span>
                    </a>
                </li>
                @endcanany
            </ul>
        </div>
    </div>
</nav>
