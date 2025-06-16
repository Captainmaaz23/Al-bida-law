

<header id="page-header">

    <div class="content-header">

        <div class="d-flex align-items-center">

            <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">

                <i class="fa fa-fw fa-bars"></i>

            </button>

            <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">

                <i class="fa fa-fw fa-ellipsis-v"></i>

            </button>

        </div>




        <div class="d-flex align-items-center">

            <div class="dropdown d-inline-block ms-2">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                    <i class="fas fa-bell"></i>
                    <span class="text-primary"  id="dothdn" style="display: none">•</span>
                </button>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 border-0 fs-sm" aria-labelledby="page-header-notifications-dropdown" style="">
                    <div class="p-2 bg-body-light border-bottom text-center rounded-top">
                        <h5 class="dropdown-header text-uppercase">Notifications</h5>
                    </div>
                    <ul class="nav-items mb-0" id="notification">

                    </ul>

                    <ul class="nav-items mb-0" id="notificationnone" style="display:none;">
                        <li >
                            <a class="text-dark d-flex py-2 ml-4 " href="javascript:void(0)">
                                <div class="flex-shrink-0 me-2 ms-3 ">
                                            <span class="">
                                                <i class="fa fa-fw fa-times-circle text-danger"></i>
                                                <span class="fw-semibold">Notification is not available</span>
                                            </span>
                                </div>
                            </a>
                        </li>
                    </ul>


                    <div class="p-2 border-top text-center" style="display: none;" id="loadmore">
                        <a class="d-inline-block fw-medium" href="{{route('check_user_arrived')}}">
                            <i class="fa fa-fw fa-arrow-down me-1 opacity-50"></i> Load More..
                        </a>
                    </div>
                </div>
            </div>










            {{-- <div id="notification"><i class="fas fa-bell text-success"></i></div> --}}


            <div class="dropdown d-inline-block ml-2">

                <button type="button" class="btn btn-sm btn-dual d-flex align-items-center" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <img class="rounded-circle" src="{{ asset_url('/media/avatars/avatar10.jpg') }}" alt="Header Avatar" style="width: 21px;">

                    <?php /*?><span class="d-none d-sm-inline-block ml-2">{{ Auth::user()->name }}</span><?php */?>

                    <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block ml-1 mt-1"></i>

                </button>

                <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 border-0" aria-labelledby="page-header-user-dropdown">

                    <div class="p-3 text-center bg-primary-dark rounded-top">

                        <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset_url('/media/avatars/avatar10.jpg') }}" alt="">

                        <p class="mt-2 mb-0 text-white font-w500">{{ Auth::user()->name }}</p>

                        <p class="mb-0 text-white-50 font-size-sm">{{ Auth::user()->company_name }}</p>

                    </div>

                    <div class="p-2">

                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_toggle">

                            <span class="font-size-sm font-w500">Profile</span>

                            {{-- <span class="badge badge-pill badge-primary ml-2">1</span> --}}

                        </a>

                        <div role="separator" class="dropdown-divider"></div>

                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">

                            <span class="font-size-sm font-w500">Log Out</span>

                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

                    </div>

                </div>

            </div>


            <?php /*?><div class="dropdown d-inline-block ml-2">

                        <button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                            <i class="fa fa-fw fa-bell"></i>

                            <span class="text-primary">•</span>

                        </button>

                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">

                            <div class="p-2 bg-primary-dark text-center rounded-top">

                                <h5 class="dropdown-header text-uppercase text-white">Notifications</h5>

                            </div>

                            <ul class="nav-items mb-0">

                                <li>

                                    <a class="text-dark media py-2" href="javascript:void(0)">

                                        <div class="mr-2 ml-3">

                                            <i class="fa fa-fw fa-check-circle text-success"></i>

                                        </div>

                                        <div class="media-body pr-2">

                                            <div class="font-w600">Notifications will appear here</div>

                                            <span class="font-w500 text-muted">15 min ago</span>

                                        </div>

                                    </a>

                                </li>

                            </ul>

                            <div class="p-2 border-top">

                                <a class="btn btn-sm btn-light btn-block text-center" href="javascript:void(0)">

                                    <i class="fa fa-fw fa-arrow-down mr-1"></i> Load More..

                                </a>

                            </div>

                        </div>

                    </div><?php */?>


            <?php /*?><button type="button" class="btn btn-sm btn-dual ml-2" data-toggle="layout" data-action="side_overlay_toggle">

                        <i class="fa fa-cogs"></i>

                    </button><?php */?>

        </div>

    </div>

    <div id="page-header-loader" class="overlay-header bg-white">

        <div class="content-header">

            <div class="w-100 text-center">

                <i class="fa fa-fw fa-circle-notch fa-spin"></i>

            </div>

        </div>

    </div>

</header>