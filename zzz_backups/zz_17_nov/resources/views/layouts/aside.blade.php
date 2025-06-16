

<aside id="side-overlay" class="font-size-sm">

    <div class="content-header border-bottom">

        <a class="img-link mr-1" href="javascript:void(0)">

            <img class="img-avatar img-avatar32" src="{{ asset_url('/media/avatars/avatar10.jpg') }}" alt="">

        </a>

        <div class="ml-2">

            <a class="text-dark font-w600 font-size-sm" href="javascript:void(0)">{{ Auth::user()->name }}</a>

        </div>

        <a class="ml-auto btn btn-sm btn-alt-danger" href="javascript:void(0)" data-toggle="layout" data-action="side_overlay_close">

            <i class="fa fa-times"></i>

        </a>

    </div>

    <div class="content-side">

        <h6>Profile Details</h6>

        <ul class="list-group mb-5">

            <li class="list-group-item d-flex justify-content-between align-items-center"><span class="text-muted"><i class="mr-2 fa fa-user-tie"></i></span><span class="bg-light rounded px-2">{{ Auth::user()->name }}</span></li>

            <li class="list-group-item d-flex justify-content-between align-items-center"><span class="text-muted"><i class="mr-2 fa fa-briefcase"></i></span><span class="bg-light rounded px-2">{{ Auth::user()->company_name }}</span></li>

            <li class="list-group-item d-flex justify-content-between align-items-center"><span class="text-muted"><i class="mr-2 fa fa-phone"></i></span><span class="bg-light rounded px-2">{{ Auth::user()->phone }}</span></li>

            <li class="list-group-item d-flex justify-content-between align-items-center"><span class="text-muted"><i class="mr-2 fa fa-envelope"></i></span><span class="bg-light rounded px-2">{{ Auth::user()->email }}</span></li>

        </ul>



        <h6>Change Password</h6>

        <form class="bg-light rounded px-4 py-2" action="{{ route('users.updatePassword') }}" method="POST">

            @csrf

            <div class="my-2">

                <label for="currentPass">Current Password</label>

                <input type="text" class="form-control" name="current_password" id="currentPass">

            </div>



            <div class="my-2">

                <label for="newPass">New Password</label>

                <input type="text" class="form-control" name="new_password" id="newPass">

            </div>



            <div class="mt-2 mb-3">

                <input type="submit" value="Change Password" class="btn btn-dark btn-block">

            </div>

        </form>

    </div>

</aside>