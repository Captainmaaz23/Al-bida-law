@extends('layouts.app')

@section('content')


<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Edit Restaurant Details:  {{ $Model_Data->name }}
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('restaurants.index') }}">Restaurants</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Details
                    </li>
                </ol>
            </nav>
            <a  href="{{ route('restaurants.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>



<div class="content">

    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Basic Details</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('restaurants.update', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                @include('restaurants.fields')


        </div>
    </div>

    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Location</h3>
        </div>
        <div class="block-content">
            @include('restaurants.locations')
        </div>
    </div>
</form>


<div class="block block-rounded block-themed">
    <div class="block-header">
        <h3 class="block-title">Social Media</h3>
        <span class="right_align">
            <a href="#" class="btn btn-alt-primary pull-right" data-toggle="modal" data-target="#socialMediaModal">Edit</a>
        </span>
    </div>
    <div class="block-content">
        <?php
        if (isset($SocialMedias) && !empty($SocialMedias)) {
            ?>
            @include('restaurants.show_social_media')
            <?php
        }
        else {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <p> No Record Found! </p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="block block-rounded block-themed">
            <div class="block-header">
                <h3 class="block-title">Working Hours</h3>
                <span class="right_align">
                    <a href="#" class="btn btn-alt-primary pull-right" data-toggle="modal" data-target="#workingHoursModal">Edit</a>
                </span>
            </div>
            <div class="block-content">
                <?php
                if (isset($WorkingHours) && !empty($WorkingHours)) {
                    ?>
                    @include('restaurants.show_working_hours')
                    <?php
                }
                else {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <p> No Record Found! </p>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="block block-rounded block-themed">
            <div class="block-header">
                <h3 class="block-title">Ramadan Working Hours</h3>
                <span class="right_align">
                    <a href="#" class="btn btn-alt-primary pull-right" data-toggle="modal" data-target="#ramadanworkingHoursModal">Edit</a>
                </span>
            </div>
            <div class="block-content">
                <?php
                if (isset($RamadanWorkingHours) && !empty($RamadanWorkingHours)) {
                    ?>
                    @include('restaurants.show_ramadan_working_hours')
                    <?php
                }
                else {
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <p> No Record Found! </p>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>


<div class="block block-rounded block-themed">
    <div class="block-header">
        <h3 class="block-title">Slides</h3>

        <span class="right_align">

            @if(Auth::user()->can('slides-add') || Auth::user()->can('all'))
            <a href="#" class="btn btn-alt-primary pull-right" data-toggle="modal" data-target="#slidesModal">
                Add
            </a>
            <?php
            if (count($slides) > 0) {
                ?>
                <a href="#" class="btn btn-alt-primary pull-right" data-toggle="modal" data-target="#slidesOrderModal">
                    Change Order
                </a>
                <?php
            }
            ?>
            @endif
        </span>
    </div>
    <div class="block-content">
        <?php
        if (isset($Slides) && !empty($Slides) && count($Slides) > 0) {
            ?>
            @include('restaurants.show_slides')
            <?php
        }
        else {
            ?>
            <div class="row">
                <div class="col-md-12">
                    <p> No Record Found! </p>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>



</div>

<?php
if (isset($SocialMedias) && !empty($SocialMedias)) {
    ?>
    <div class="modal" id="socialMediaModal" tabindex="-1" role="dialog" aria-labelledby="socialMediaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Edit Social Media</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <?php
                    $instagram = $SocialMedias->instagram;
                    $twitter = $SocialMedias->twitter;
                    $tiktok = $SocialMedias->tiktok;
                    $instagram = str_replace("https://www.instagram.com/", "", $instagram);
                    $twitter = str_replace("https://www.twitter.com/", "", $twitter);
                    $tiktok = str_replace("https://www.tiktok.com/", "", $tiktok);
                    ?>
                    <form action="{{ route('restaurant-social-medias.update', $SocialMedias->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="block-content fs-sm">
                            @include('restaurants.social_media')
                            <div class="mt-4 mb-3 row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="#" class='btn btn-outline-dark' id="btnclose" data-dismiss="modal">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <?php
}
else {
    ?>
    <div class="modal" id="socialMediaModal" tabindex="-1" role="dialog" aria-labelledby="socialMediaModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Add Social Media</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('restaurant-social-medias.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="rest_id" name="rest_id" value="{{ $Model_Data->id }}" />

                        <div class="block-content fs-sm">
                            @include('restaurants.social_media')
                            <div class="mt-4 mb-3 row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="#" class='btn btn-outline-dark' id="btnclose" data-dismiss="modal">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php
if (isset($WorkingHours) && !empty($WorkingHours) && ($WorkingHours->type == 0)) {
    ?>
    <div class="modal" id="workingHoursModal" tabindex="-1" role="dialog" aria-labelledby="workingHoursModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Edit Working Hours</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form action="{{ route('restaurant-working-hours.update', $WorkingHours->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="block-content fs-sm">
                            @include('restaurants.edit_working_hours')
                            <div class="mt-4 mb-3 row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="#" class='btn btn-outline-dark' id="btnclose" data-dismiss="modal">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}
else {
    ?>
    <div class="modal" id="workingHoursModal" tabindex="-1" role="dialog" aria-labelledby="workingHoursModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Add Working Hours</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form action="{{ route('restaurant-working-hours.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="rest_id" name="rest_id" value="{{ $Model_Data->id }}" />

                        <div class="block-content fs-sm">
                            @include('restaurants.edit_working_hours')
                            <div class="mt-4 mb-3 row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="#" class='btn btn-outline-dark' id="btnclose" data-dismiss="modal">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<?php
// Function to render modal content
function renderModal($title, $formAction, $method, $ramadanWorkingHours = null, $isEdit = false) {
    ?>
    <div class="modal" id="ramadanworkingHoursModal" tabindex="-1" role="dialog" aria-labelledby="workingHoursModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">{{ $title }}</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method($method)

                        <div class="block-content fs-sm">
                            @include('restaurants.edit_ramadan_working_hours')
                            <div class="mt-4 mb-3 row">
                                <div class="col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="#" class='btn btn-outline-dark' data-dismiss="modal">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// Check if RamadanWorkingHours exists and render appropriate modal
if (isset($RamadanWorkingHours) && !empty($RamadanWorkingHours)) {
    renderModal('Edit Ramadan Working Hours', route('restaurant-ramadan-working-hours.update', $RamadanWorkingHours->id), 'PATCH', $RamadanWorkingHours, true);
} else {
    renderModal('Add Ramadan Working Hours', route('restaurant-ramadan-working-hours.store'), 'POST');
}
?>

@if(Auth::user()->can('slides-add') || Auth::user()->can('all'))
<div class="modal" id="slidesModal" tabindex="-1" role="dialog" aria-labelledby="slidesModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Add New</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>

                <form action="{{ route('slides.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="block-content fs-sm">
                        @include('restaurants.slides.fields')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@if (count($slides) > 0)
<div class="modal" id="slidesOrderModal" tabindex="-1" role="dialog" aria-labelledby="slidesOrderModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Change Slide Order</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-1">&nbsp;</div>
                    <div class="col-lg-10">
                        <form action="{{ route('restaurants_slides.order', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="table-responsive">
                                <div class="table-container menyTable-container">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr role="row" class="heading">
                                                <th>#</th>
                                                <th>Slide</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($slides as $index => $Slide)
                                            <tr>
                                                <td>
                                                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                    <span class="spncount">{{ $index + 1 }}</span>
                                                    <input type="hidden" name="slide_order[]" value="{{ $Slide->id }}" />
                                                </td>
                                                <td>
                                                    <?php
                                                    $image = $Slide->image;
                                                    $image_path = $image == 'rest_slide.png' ? 'defaults/' : 'restaurants/slides/';
                                                    $image_path .= $image;
                                                    $image_path = uploads($image_path);
                                                    ?>
                                                    <a href="{{ $image_path }}" target="_blank" title="View Slide">
                                                        <img src="{{ $image_path }}" style="height:80px; width:100%;">
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4 mb-3 row">
                                <div class="col-sm-12">
                                    <hr />
                                </div>
                                <div class="form-group col-sm-12 text-right">
                                    <button type="submit" class="btn btn-primary">Save Order</button>
                                    <a href="{{ route('restaurants.index') }}" class="btn btn-outline-dark">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-1">&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
    <div class="col-sm-12">No records found</div>
</div>
@endif


<div class="col-sm-6 clone" style="display: none;">
    <input type="hidden" name="cat_id[]" value="" class="form-control cat_id">
</div>
<div class="col-sm-6 menu-clone" style="display: none;">
    <input type="hidden" name="menu_id[]" value="web" class="form-control menu_id">
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        // Handle radio button clicks
        $(document).on('click', '.radioBtn a, .radioBtnb a', function () {
            const isRadioA = $(this).closest('.radioBtn').length > 0;
            const sel = $(this).data(isRadioA ? 'title' : 'titleb');
            const tog = $(this).data(isRadioA ? 'toggle' : 'toggleb');
            $('#is_' + tog).val(sel);

            $('a[data-toggle="' + tog + '"]').removeClass('active').addClass('notActive');
            $(this).removeClass('notActive').addClass('active');

            const isVisible = sel == 1;
            $('.' + tog + '_from_div, .' + tog + '_to_div').toggleClass('hide', !isVisible);
        });

        // Update time fields on change
        $(".available_from, .available_to").change(function () {
            const index = $(".available_from").index(this);
            const from = parseInt($(".available_from").eq(index).val(), 10);
            const to = parseInt($(".available_to").eq(index).val(), 10);

            if (from >= to) {
                $(".available_to").eq(index).val(from + 1);
            }
        });

        // Close modals
        $(document).on('click', '.btn_close_modal', function () {
            const modalId = $(this).data('modal');
            $(`#${modalId}`).modal('hide');
        });

        // Handle category and menu selections
        $(".cat_title, .menu_title").change(function () {
            const isCategory = $(this).hasClass('cat_title');
            const selectedVal = $(this).val();
            const selectedHtml = $(this).find(":selected").html();
            const selector = isCategory ? '.slected_catgories' : '.slected_menus';
            const cloneClass = isCategory ? '.clone' : '.menu-clone';
            const variationDiv = isCategory ? '.variation_div' : '.menu_variation_div';

            if (selectedVal) {
                const currentCategories = $(selector).html();
                const newCategories = currentCategories ? currentCategories + ' , ' + selectedHtml : selectedHtml;
                $(selector).html(newCategories);

                const html = $(cloneClass).html();
                $(variationDiv).append(html);
                $(`${cloneClass} .${isCategory ? 'cat_id' : 'menu_id'}`).val(selectedVal);
            }
        });

        // Enable sorting on table rows
        $("tbody").sortable({
            update: slideCount
        });
    });

    function slideCount() {
        $('.spncount').each(function (index) {
            $(this).html(index + 1);
        });
    }
</script>
@endpush