@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">Create New Item</h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('items.index') }}">Items</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Create New</li>
                </ol>
            </nav>
            <a href="{{ route('items.index') }}" class="btn btn-dark btn-return">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Create New Item</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" id="myForm">
                @csrf
                @include('restaurants.items.fields')
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // Format price input on change
    $('#myForm').on('change', 'input[name=price]', function () {
        const price = parseFloat($(this).val()).toFixed(3);
        $(this).val(price);
    });

    // Load menus based on restaurant selection
    $('#rest_id').on('change', function () {
        const menuid = $('#menu_id');
        menuid.empty().append('<option value="">Select</option>');

        $.get(`{{ url('/') }}/restaurants/${this.value}/menus.json`, function (menus) {
            if (menus.length) {
                $.each(menus, function (index, menu) {
                    menuid.append(`<option value="${menu.id}">${menu.title}</option>`);
                });
                menu_select2();
            }
        });
    });

    // Calculate subtotal based on discount and price
    function calculateTotal() {
        const price = parseFloat($('.txt_price').val()) || 0;
        const discount = parseFloat($('.txt_discount').val()) || 0;
        const subtotal = (1 - (discount / 100)) * price;
        $('.txt_total').val(subtotal.toFixed(2));
    }

    // Bind change events for discount and price
    $('.txt_discount, .txt_price').on('change', calculateTotal);
});
</script>
@endpush
