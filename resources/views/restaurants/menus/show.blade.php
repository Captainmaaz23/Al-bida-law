@extends('layouts.app')

@section('content')

<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Menu Details: {{ $Model_Data->title }} 
            </h1>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <nav class="flex-sm-00-auto" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('dashboard') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('menus.index') }}">Menus</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        View Details
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('menus.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>

<div class="content">                
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Menu Details</h3>
        </div>
        <div class="block-content">
            @include('restaurants.menus.show_fields')
        </div>
    </div>                            
    
    <div class="block block-rounded block-themed">
        <div class="block-header">
            <h3 class="block-title">Menu Items Details</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('menus.order', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('restaurants.menus.items_show')
            </form>
        </div>
    </div>                    
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>  
<style>
    table {
        border-spacing: collapse;
        overflow: hidden;
    }
    td {
        height: 25px;
        border: 1px solid black;
    }
    .ui-draggable, .ui-droppable {
        background-position: top;
    }
</style>
<script>
$(document).ready(function () {
    $("tbody").sortable({
        update: function () {
            updateItemCount();
        }
    });
});

function updateItemCount() {
    $('.spncount').each(function (index) {
        $(this).text(index + 1);
    });
}
</script>
@endpush