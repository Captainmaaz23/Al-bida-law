@extends('layouts.app')

@section('content')
<?php
$AUTH_USER = Auth::user();
?>

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Restaurant Details:  {{ $Model_Data->name }}
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
                        View Details
                    </li>
                </ol>
            </nav>
            <a href="{{ route('restaurants.index') }}" class="btn btn-dark btn-return pull-right">
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
            @if(Auth::user()->rest_id == 0)
            <a class="btn btn-dark" href="{{ route('rest-orders', $Model_Data->id) }}" title="View Details">View Orders</a>
            @else
            <a class="btn btn-dark" href="{{ route('restaurants.edit', $Model_Data->id) }}" title="Edit Details">Edit details</a>
            @endif
        </div>
        <div class="block-content">
            @include('restaurants.show_fields')
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Contact Details</h3>
                </div>
                <div class="block-content">
                    @include('restaurants.show_contact_details')
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Social Media</h3>
                </div>
                <div class="block-content">
                    @if(!empty($SocialMedias))
                    @include('restaurants.show_social_media')
                    @else
                    <p>No Record Found!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Location</h3>
                </div>
                <div class="block-content">
                    @include('restaurants.show_location_details')
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Bank Information</h3>
                </div>
                <div class="block-content">
                    @if(!empty($BankDetail) && count($BankDetail) > 0)
                        @include('restaurants.show_bank_detail')
                    @else
                    <p>No Record Found!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mb-3">
        <div class="col-lg-6">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Working Hours</h3>
                </div>
                <div class="block-content">
                    @if(!empty($WorkingHours))
                    @include('restaurants.show_working_hours')
                    @else
                    <p>No Record Found!</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Ramadan Working Hours</h3>
                </div>
                <div class="block-content">
                    @if(!empty($RamadanWorkingHours))
                    @include('restaurants.show_ramadan_working_hours')
                    @else
                    <p>No Record Found!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Slides</h3>
                </div>
                <div class="block-content">
                    @if(!empty($Slides) && count($Slides) > 0)
                    <div class="row items-push js-gallery img-fluid-100 js-gallery-enabled">
                        @foreach($Slides as $Slide)
                        @php
                        $image_path = ($Slide->image == 'rest_slide.png') 
                        ? 'defaults/' 
                        : 'restaurants/slides/';
                        $image_path .= $Slide->image;
                        $image_path = uploads($image_path);
                        @endphp

                        <div class="col-md-6 col-lg-4 col-xl-3 animated fadeIn">
                            <a class="img-link img-link-zoom-in img-thumb img-lightbox" href="{{ $image_path }}">
                                <img class="img-fluid" src="{{ $image_path }}" alt="Slide">
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p>No Record Found!</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Pdf Files</h3>
                </div>
                <div class="block-content">
                    @if(count($Pdf) > 0)
                    <div class="table-responsive">
                        <div class="table-container">
                            <table class="table table-striped table-hover" id="myDataTable1">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th>Title</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-sm-12">No records found</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Menus</h3>
                    @if(count($menus) > 0)
                    <span class="right_align">
                        <a href="#" class="btn btn-alt-primary pull-right" data-toggle="modal" data-target="#menuOrderModal">Change Order</a>
                    </span>
                    @endif
                </div>
                <div class="block-content">
                    @if(count($Menus) > 0)
                    <div class="table-responsive">
                        <div class="table-container">
                            <table class="table table-striped table-hover" id="myDataTable">
                                <thead>
                                    <tr role="row" class="heading">
                                        <th>Order</th>
                                        <th>Title</th>
                                        <th>Items</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-sm-12">No records found</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Orders</h3>
                </div>
                <div class="block-content">
                    @include('restaurants.user_orders')
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="block block-rounded block-themed">
                <div class="block-header">
                    <h3 class="block-title">Reviews</h3>
                </div>
                <div class="block-content">
                    @include('restaurants.user_reviews')
                </div>
            </div>
        </div>
    </div>

<div>

@if(count($menus) > 0)
    <div class="modal" id="menuOrderModal" tabindex="-1" role="dialog" aria-labelledby="menuOrderModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Change Menus Order</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-lg-1">&nbsp;</div>
                        <div class="col-lg-10">
                            <form action="{{ route('restaurants_menus.order', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="table-responsive">
                                    <div class="table-container menyTable-container">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr role="row" class="heading">
                                                    <th>#</th>
                                                    <th>Menu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($menus as $index => $menu)
                                                    <tr>
                                                        <td>
                                                            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                            <span class="spncount">{{ $index + 1 }}</span>
                                                            <input type="hidden" name="menu_order[]" value="{{ $menu->id }}" />
                                                        </td>
                                                        <td>{{ $menu->title }}</td>
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
@endif

@endsection

@section('headerInclude')
    @include('datatables.css')
@endsection

@section('footerInclude')
    @include('datatables.js')
@endsection

@push('scripts')
<script>
    jQuery(document).ready(function (e) {
        
        // Function to initialize DataTable
        function initializeDataTable(tableId, url, columns, filters = {}) {
            return $(tableId).DataTable({
                processing: true,
                serverSide: true,
                stateSave: true,
                searching: false,
                dom: 'Blfrtip',
                autoWidth: false,
                buttons: [
                    { extend: 'excel', exportOptions: { columns: ':visible' } },
                    { extend: 'pdf', exportOptions: { columns: ':visible' } },
                    { extend: 'print', exportOptions: { columns: ':visible' } },
                    'colvis'
                ],
                columnDefs: [{ targets: -1, visible: true }],
                ajax: {
                    url: url,
                    data: function (d) {
                        // Pass additional filter data if available
                        Object.assign(d, filters);
                    }
                },
                columns: columns
            });
        }

        // Common filters
        var commonFilters = {
            order_no: $('#s_order_no_2').val(),
            order_value: $('#s_order_value').val(),
            order_status: $('#s_order_status').val()
        };

        // Initialize first DataTable
        var oTable = initializeDataTable(
            '#myDataTable',
            "{{ route('restaurants_menu_datatable', $Model_Data->id) }}",
            [
                { data: 'order', name: 'order' },
                { data: 'title', name: 'title' },
                { data: 'items', name: 'items' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', searchable: false, orderable: false }
            ]
        );

        // Initialize second DataTable (only if UserOrders exists)
        @if ($UserOrders_exists == 1)
            var oTable2 = initializeDataTable(
                '#myDataTable2',
                "{{ route('rest_order_datatable', $Model_Data->id) }}",
                [
                    { data: 'order_no', name: 'order_no' },
                    { data: 'order_value', name: 'order_value' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' }
                ],
                commonFilters
            );

            // Filter event listeners for oTable2
            $('#s_order_no_2, #s_order_value, #s_order_status').on('keyup change', function (e) {
                oTable2.draw();
                e.preventDefault();
            });
        @endif

        // Initialize third DataTable (only if UserReviews exists)
        @if ($UserReviews_exists == 1)
            var oTable3 = initializeDataTable(
                '#myDataTable3',
                "{{ route('rest_review_datatable', $Model_Data->id) }}",
                [
                    { data: 'order_no', name: 'order_no' },
                    { data: 'rating', name: 'rating' },
                    { data: 'review', name: 'review' },
                    { data: 'badwords_found', name: 'badwords_found' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action' }
                ],
                {
                    order_no: $('#s_order_no').val(),
                    rating: $('#s_rating').val(),
                    review: $('#s_review').val(),
                    badword: $('#s_badword').val(),
                    status: $('#s_status').val()
                }
            );

            // Filter event listeners for oTable3
            $('#s_order_no, #s_rating, #s_review, #s_badword, #s_status').on('keyup change', function (e) {
                oTable3.draw();
                e.preventDefault();
            });
        @endif

    });
</script>
<link rel="stylesheet" href="{{ asset_url('js/plugins/magnific-popup/magnific-popup.css') }}">
<script src="{{ asset_url('js/plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script>
    $(function () {
        $('div.js-gallery').magnificPopup({delegate: 'a', type: 'image', gallery: {enabled: true}});
    });
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCD8bY_JqPR9R6H-PaCp06DMc1dpyFaFbg"></script>
<?php
$segment1 = Request::segment(3);
$location = null;
$lat = -34.397;
$lng = 150.644;

if (isset($Model_Data)) {
    $location = $Model_Data->location;
    $lat = $Model_Data->lat;
    $lng = $Model_Data->lng;
}
?>

<script>
    const mapApp = {
        geocoder: null,
        map: null,
        infoWindow: null,
        marker: null,

        initMap: function() {
            this.geocoder = new google.maps.Geocoder();
            this.infoWindow = new google.maps.InfoWindow();

            this.map = new google.maps.Map(document.getElementById("mymap"), {
                zoom: 8,
                center: { lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?> }
            });

            this.marker = new google.maps.Marker({
                map: this.map,
                position: { lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?> }
            });

            document.getElementById("searchmap").addEventListener("change", () => this.geocodeAddress());
            google.maps.event.addListener(this.map, "click", (e) => this.handleMapClick(e));
        },

        handleMapClick: function(e) {
            const latLng = e.latLng;
            $("#lat").val(latLng.lat());
            $("#lng").val(latLng.lng());

            if (this.marker && this.marker.setMap) {
                this.marker.setMap(null);
            }
            this.marker = new google.maps.Marker({
                position: latLng,
                map: this.map
            });

            this.infoWindow.setPosition(latLng);
            this.infoWindow.setContent(`You have selected:<br>Lat: ${latLng.lat()}<br>Long: ${latLng.lng()}`);
            this.infoWindow.open(this.map, this.marker);
        },

        geocodeAddress: function() {
            const address = document.getElementById("searchmap").value;
            this.geocoder.geocode({ address: address }, (results, status) => {
                if (status === "OK") {
                    this.map.setCenter(results[0].geometry.location);
                    new google.maps.Marker({
                        map: this.map,
                        position: results[0].geometry.location
                    });
                } else {
                    alert(`Geocode was not successful for the following reason: ${status}`);
                }
            });
        }
    };

    // Initialize the map
    mapApp.initMap();
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $(document).ready(function () {
        $("tbody").sortable({
            update: function () {
                updateSlideCount();
            }
        });
    });

    function updateSlideCount() {
        const countElements = $('.spncount');
        countElements.each(function (index) {
            $(this).html(index + 1); // Update the inner HTML to show the current index + 1
        });
    }
</script>



    @endpush
