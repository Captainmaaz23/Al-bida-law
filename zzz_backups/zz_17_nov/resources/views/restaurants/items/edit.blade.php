@extends('layouts.app')

@section('content')
<?php
$AUTH_USER = Auth::user();
?>



<div class="bg-body-light">
    <div class="content">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <h1 class="flex-sm-fill h3 my-2">
                Edit Item Details:  {{ $Model_Data->name }} 
            </h1>
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
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit Details
                    </li>
                </ol>
            </nav>               
            <a href="{{ route('items.index') }}" class="btn btn-dark btn-return pull-right">
                <i class="fa fa-chevron-left mr-2"></i> Return to Listing
            </a>
        </div>
        @include('flash::message')
    </div>
</div>



<div class="content">                
     
    <div class="block block-rounded block-themed">
        <ul class="nav nav-tabs nav-tabs-block" role="tablist">

            <li class="nav-item">
                <button class="nav-link tab_link active" id="btabs-static-item-tab" data-bs-toggle="tab" data-bs-target="#btabs-static-item" role="tab" aria-controls="btabs-static-item" aria-selected="false">Item Details</button>
            </li>

            <li class="nav-item">
                <button class="nav-link tab_link" id="btabs-static-addon-tab" data-bs-toggle="tab" data-bs-target="#btabs-static-addon" role="tab" aria-controls="btabs-static-addon" aria-selected="true">Addons</button>
            </li>

            <li class="nav-item">
                <button class="nav-link tab_link" id="btabs-static-addon-type-tab" data-bs-toggle="tab" data-bs-target="#btabs-static-addon-type" role="tab" aria-controls="btabs-static-addon-type" aria-selected="true">Addon Types</button>
            </li>
        </ul>
        <div class="block-content tab-content">

            <div class="tab-pane active" id="btabs-static-item" role="tabpanel" aria-labelledby="btabs-static-item-tab">

                <form action="{{ route('items.update', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    @include('restaurants.items.fields')

                </form>

            </div>

            <div class="tab-pane" id="btabs-static-addon" role="tabpanel" aria-labelledby="btabs-static-addon-tab">

                <form action="{{ route('update_addons', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <?php
                    if (isset($Model_Data)) {
                        $style = '';
                        if (isset($Model_Data->has_options) && $Model_Data->has_options == 1) {
                            $style = ' style="display:none;"';
                        } {
                            ?>
                            <div class="row" <?php echo $style; ?>>
                                <div class="form-group col-sm-6">
                                    <input id="chk_variations" name="variations" type="checkbox"  <?php
                                    if (isset($Model_Data->has_options) && $Model_Data->has_options == 1) {
                                        echo 'checked="checked"';
                                    }
                                    ?>>
                                    <label for="chk_variations">has Addons:</label>
                                </div>
                            </div>
                            <?php
                        }
                        ?>


                        <div id="variation_div">
                            <?php
                            if (isset($Model_Data->has_options) && $Model_Data->has_options == 1) {
                                $count = 0;
                                foreach ($variants as $variant) {
                                    $count++;
                                    if ($count == 1) {
                                        ?>
                                        <div class="variations"  style="padding: 10px;">

                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <span class="addon_count"><strong>Addon <?php echo $count; ?></strong></span>
                                                </div>    
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <select name="type_id[]" class="form-control" placeholder="Select Addon type">
                                                        <option value="" selected disabled>Select Addon type</option>
                                                        @foreach ($addon_types as $value => $label)
                                                        <option value="{{ $value }}" {{ in_array($value, (array) $variant->type_id) ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <input type="text" name="var_price[]" value="<?php echo get_decimal($variant->price); ?>" placeholder="Price" class="form-control price_addon">
                                                </div>
                                                <?php /* ?><div class="form-group col-sm-4">
                                                  <input type="file" name="var_image[]" class="form-control">
                                                  </div><?php */ ?>
                                                <div class="form-group col-sm-2 text-right">
                                                    <button type="button" class="btn btn-primary add_variations" id="add_variations">+</button>
                                                </div>    
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <input type="text" name="var_name[]" value="<?php echo $variant->name; ?>" placeholder="Name [ English ]" class="form-control">
                                                </div>
                                                <?php /* ?><div class="form-group col-sm-6">
                                                  <input type="text" name="var_ar_name[]" value="<?php echo $variant->ar_name;?>" placeholder="Name [ Arabic ]" class="form-control">
                                                  </div> <?php */ ?>   
                                            </div>

                                            <?php /* ?><div class="row">
                                              <div class="form-group col-sm-6 col-lg-6">
                                              <textarea name="var_description[]" class="form-control" placeholder="Description [ English ]" cols="10" rows="1" maxlength="200">{{ $variant->description }}</textarea>

                                              </div>
                                              <div class="form-group col-sm-6 col-lg-6">
                                              <textarea name="var_ar_description[]" class="form-control" placeholder="Description [ Arabic ]" cols="10" rows="1" maxlength="200">{{ $variant->ar_description }}</textarea>

                                              </div>
                                              </div><?php */ ?>

                                        </div>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <div class="variations variationrow"  style=" margin-top: 10px; padding: 10px;">

                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <span class="addon_count"><strong>Addon <?php echo $count; ?></strong></span>
                                                </div>    
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-sm-6">
                                                    <select name="type_id[]" class="form-control" placeholder="Select Addon type">
                                                        <option value="" selected disabled>Select Addon type</option>
                                                        @foreach ($addon_types as $value => $label)
                                                        <option value="{{ $value }}" {{ $value == $variant->type_id ? 'selected' : '' }}>{{ $label }}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <input type="text" name="var_price[]" value="<?php echo get_decimal($variant->price); ?>" placeholder="Price" class="form-control price_addon">
                                                </div>
                                                <?php /* ?><div class="form-group col-sm-4">
                                                  <input type="file" name="var_image[]" class="form-control">
                                                  </div><?php */ ?>
                                                <div class="form-group col-sm-2 text-right">
                                                    <button type="button" class="btn btn-secondary btn-rem-var">X</button>
                                                    <button type="button" class="btn btn-primary add_variations" id="add_variations">+</button>
                                                </div>    
                                            </div>

                                            <div class="row">
                                                <div class="form-group col-sm-12">
                                                    <input type="text" name="var_name[]" value="{{ $variant->name }}" placeholder="Name [ English ]" class="form-control">
                                                </div>
                                                <?php /* ?><div class="form-group col-sm-6">
                                                  <input type="text" name="var_ar_name[]" value="" placeholder="Name [ Arabic ]" class="form-control">
                                                  </div><?php */ ?>    
                                            </div>

                                            <?php /* ?><div class="row">
                                              <div class="form-group col-sm-6 col-lg-6">
                                              <textarea name="var_description[]" class="form-control" placeholder="Description [ English ]" cols="10" rows="1" maxlength="200">{{ $variant->description }}</textarea>

                                              </div>
                                              <div class="form-group col-sm-6 col-lg-6">
                                              <textarea name="var_ar_description[]" class="form-control" placeholder="Description [ Arabic ]" cols="10" rows="1" maxlength="200">{{ $variant->ar_description }}</textarea>

                                              </div>
                                              </div><?php */ ?>

                                        </div>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>


                    <div class="form-group col-sm-12 text-right">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                </form>
            </div>

            <div class="tab-pane" id="btabs-static-addon-type" role="tabpanel" aria-labelledby="btabs-static-addon-type-tab">
                @if(Auth::user()->can('addon-types-add') || Auth::user()->can('all'))              
                <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-add-new pull-right">
                    <i class="fa fa-plus-square fa-lg"></i> Add New
                </a>
                @endif

                @if($addons_exists == 1)                   

                <form method="post" role="form" id="data-search-form">

                    <input type="hidden" id="s_item_id" value="{{ $Model_Data->id }}" />

                    <div class="table-responsive">

                        <table class="table table-striped table-hover"  id="myDataTable">

                            <thead>

                                <tr role="row" class="heading">                                                 

                                    <td>
                                        <input type="text" class="form-control" id="s_name" autocomplete="off" placeholder="Name">
                                    </td>  


                                    <td>
                                        <select class="form-control" id="s_mandatory">
                                            <option value="-1">Select</option>
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>
                                        </select>
                                    </td>


                                    <td>
                                        <select class="form-control" id="s_multi_select">
                                            <option value="-1">Select</option>
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>
                                        </select>
                                    </td>                                                 

                                    <td>
                                        <input type="text" class="form-control" id="s_maximum" autocomplete="off" placeholder="Maximum Selection" min="0" max="10">
                                    </td> 

                                    <td>&nbsp;</td>

                                </tr>

                                <tr role="row" class="heading">

                                    <th>Name</th>

                                    <th>Mandatory</th>   

                                    <th>MultiSelect</th>  

                                    <th>Maximum Selection</th>   

                                    <th>Action</th>                                                    

                                </tr>

                            </thead>

                            <tbody>

                            </tbody>

                        </table>

                    </div>
                </form>

                @else

                <p style="text-align:center; font-weight:bold; padding:50px;">No Records Available</p>

                @endif
            </div>
        </div>
    </div>  
</div>




@if(Auth::user()->can('addon-types-add') || Auth::user()->can('all'))
<?php
$Items = array();
$Items[$Model_Data->id] = $Model_Data->name;
?>
<div class="modal" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Add New Addon Type</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option btn_close_modal" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>

                <form action="{{ route('addon-types.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="block-content fs-sm">
                        @include('addon_types.fields')
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endif

<div id="variation_data" style="display: none;">
    <div class="variations"  style="padding: 10px;">

        <?php
        if (count($addon_types) == 0) {
            ?>    
            <div class="row">
                <div class="form-group col-sm-6">
                    <strong>Addon</strong>
                </div>    
            </div>    
            <div class="row">
                <div class="form-group col-sm-12">
                    <p>Please add Addon Types for this item first.</p>

                    @if(Auth::user()->can('addon-types-add') || Auth::user()->can('all'))              
                    <a href="#" data-toggle="modal" data-target="#createModal" class="btn btn-primary btn-add-new pull-right">
                        <i class="fa fa-plus-square fa-lg"></i> Click here to Add
                    </a>
                    @endif
                </div>    
            </div>

            <?php
        }
        else {
            ?>    
            <div class="row">
                <div class="form-group col-sm-6">
                    <span class="addon_count"><strong>Addon</strong></span>
                </div>    
            </div>

            <div class="row">
                <div class="form-group col-sm-6">
                    <select name="type_id[]" class="form-control" placeholder="Select Addon type">
                        <option value="" selected disabled>Select Addon type</option>
                        @foreach ($addon_types as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>

                </div> 
                <div class="form-group col-sm-4">
                    <div class="input-group">	
                        <input type="text" name="var_price[]" value="" placeholder="Price" class="form-control price_addon">
                        <span class="input-group-text">PKR</span>
                    </div>
                </div>
                <?php /* ?><div class="form-group col-sm-4">
                  <input type="file" name="var_image[]" class="form-control">
                  </div><?php */ ?>
                <div class="form-group col-sm-2 text-right">
                    <button type="button" class="btn btn-primary add_variations" id="add_variations">+</button>
                </div>  
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <input type="text" name="var_name[]" value="" placeholder="Name [ English ]" class="form-control">
                </div>
                <?php /* ?><div class="form-group col-sm-6">
                  <input type="text" name="var_ar_name[]" value="" placeholder="Name [ Arabic ]" class="form-control">
                  </div> <?php */ ?>   
            </div>

            <?php /* ?><div class="row">
              <div class="form-group col-sm-6 col-lg-6">
              <textarea name="var_description[]" class="form-control" placeholder="Description [ English ]" cols="10" rows="1" maxlength="200"></textarea>

              </div>
              <div class="form-group col-sm-6 col-lg-6">
              <textarea name="var_ar_description[]" class="form-control" placeholder="Description [ Arabic ]" cols="10" rows="1" maxlength="200"></textarea>

              </div>
              </div><?php */ ?>
            <?php
        }
        ?>

    </div>
</div>

<div class="clone hide" style="display: none;">
    <div class="variations variationrow"  style=" margin-top: 10px; padding: 10px;">

        <div class="row">
            <div class="form-group col-sm-6">
                <span class="addon_count"><strong>Addon</strong></span>
            </div>    
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <select name="type_id[]" class="form-control" placeholder="Select Addon type">
                    <option value="" selected disabled>Select Addon type</option>
                    @foreach ($addon_types as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>

            </div>   
            <div class="form-group col-sm-4">
                <div class="input-group">
                    <input type="text" name="var_price[]" value="" placeholder="Price" class="form-control price_addon">
                    <span class="input-group-text">PKR</span>
                </div>
            </div>
            <?php /* ?><div class="form-group col-sm-4">
              <input type="file" name="var_image[]" class="form-control">
              </div><?php */ ?>
            <div class="form-group col-sm-2 text-right">
                <button type="button" class="btn btn-secondary btn-rem-var">X</button>
                <button type="button" class="btn btn-primary add_variations" id="add_variations">+</button>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-12">
                <input type="text" name="var_name[]" value="" placeholder="Name [ English ]" class="form-control">
            </div>
            <?php /* ?><div class="form-group col-sm-6">
              <input type="text" name="var_ar_name[]" value="" placeholder="Name [ Arabic ]" class="form-control">
              </div>  <?php */ ?>  
        </div>

        <?php /* ?><div class="row">
          <div class="form-group col-sm-6 col-lg-6">
          <textarea name="var_description[]" class="form-control" placeholder="Description [ English ]" cols="10" rows="1" maxlength="200"></textarea>

          </div>
          <div class="form-group col-sm-6 col-lg-6">
          <textarea name="var_ar_description[]" class="form-control" placeholder="Description [ Arabic ]" cols="10" rows="1" maxlength="200"></textarea>
          </div>
          </div><?php */ ?>

    </div>
</div>

@endsection

@if($addons_exists == 1)

@section('headerInclude')
@include('datatables.css')
@endsection

@section('footerInclude')
@include('datatables.js')
@endsection

@endif

@push('scripts')
<script>
    jQuery(document).ready(function (e) {


<?php
if ($addons_exists == 1) {
    ?>

            var oTable = $('#myDataTable').DataTable({

                processing: true,

                serverSide: true,

                stateSave: true,

                searching: false,

                Filter: true,

                dom: 'Blfrtip',

                autoWidth: false,

                buttons: [
                    /*{
                     extend: 'copy',
                     exportOptions: {
                     columns: ':visible'
                     }
                     },*/
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                columnDefs: [{
                        targets: -1,
                        visible: true
                    }],

                ajax: {

                    url: "{!! route('addon_types_datatable') !!}",

                    data: function (d) {

                        d.item_id = $('#s_item_id').val();

                        d.name = $('#s_name').val();

                        d.is_mandatory = $('#s_mandatory').val();

                        d.is_multi_select = $('#s_multi_select').val();

                        d.max_selection = $('#s_maximum').val();

                    }

                }, columns: [

                    {data: 'name', name: 'name'},

                    {data: 'is_mandatory', name: 'is_mandatory'},

                    {data: 'is_multi_select', name: 'is_multi_select'},

                    {data: 'max_selection', name: 'max_selection'},

                    {data: 'action', name: 'action'}

                ]

            });

            $('#data-search-form').on('submit', function (e) {

                oTable.draw();

                e.preventDefault();

            });


            $('#s_item_id').on('change', function (e) {

                oTable.draw();

                e.preventDefault();

            });

            $('#s_name').on('keyup', function (e) {

                oTable.draw();

                e.preventDefault();

            });


            $('#s_mandatory').on('change', function (e) {

                oTable.draw();

                e.preventDefault();

            });


            $('#s_multi_select').on('change', function (e) {

                oTable.draw();

                e.preventDefault();

            });


            $('#s_maximum').on('keyup', function (e) {

                oTable.draw();

                e.preventDefault();

            });

    <?php
}
?>

        if (jQuery('.btn_close_modal'))
        {
            jQuery('.btn_close_modal').click(function (e) {
                $('#createModal').modal('hide');
            });
        }
    });
</script>
<script >

    $(document).ready(function () {

        $('.tab_link').click(function ()
        {
            var target_id = $(this).data('bs-target');

            $('.tab_link').removeClass('active');
            $(this).addClass('active');
            $('.tab-pane').removeClass('active');
            $(target_id).addClass('active');
        });

        $('#myForm').find('input[name=price]').change(function ()
        {
            var price = $(this).val();
            console.log(price);
            {
                price = parseFloat(price).toFixed(3);
                $(this).val(price);
            }

        });

        jQuery('#rest_id').on('change', function (event)
        {
            jQuery('#menu_id').html('');
            var menuid = jQuery('#menu_id');
            console.log(this.value);
            jQuery.get('{{URL::to("/")}}/restaurants/' + this.value + '/menus.json', function (menus)
            {
                menuid.find('option').remove().end();
                menuid.append('<option value="">Select</option>');

                if (menus.length > 0)
                {
                    jQuery.each(menus, function (index, menu)
                    {
                        menuid.append('<option value="' + menu.id + '">' + menu.title + '</option>');
                    });
                    menu_select2();
                }
            });
        });

        $("#chk_variations").change(function () {
            if ($(this).prop('checked'))
            {
                $("#variation_div").show();
                if ($("#variation_div").children().length == 0)
                {
                    var html = $("#variation_data").html();

                    $("#variation_div").append(html);
                }
                call_addrem_addons();
            }
            else
            {
                $("#variation_div").hide();
            }
        });

        $(".txt_discount").change(function () {
            var disount = $(this).val();
            var price = $('.txt_price').val();
            var sub_total = (disount / 100) * price;
            sub_total = Math.round(price - sub_total).toFixed(2);
            var total = $('.txt_total').val(sub_total);
        });

        $(".txt_price").change(function () {
            var price = $(this).val();
            var disount = $('.txt_discount').val();
            var sub_total = (disount / 100) * price;
            sub_total = Math.round(price - sub_total).toFixed(2);
            var total = $('.txt_total').val(sub_total);
        });

        call_addrem_addons();

    });

    function call_addrem_addons()
    {

        $(".add_variations").off();
        $(".add_variations").click(function ()
        {
            var html = $(".clone").html();

            $("#variation_div").append(html);
            call_addrem_addons();
        });

        $("body").on("click", ".btn-rem-var", function () {
            $(this).parents(".form-group").parents(".row").parents(".variationrow").remove();
            addon_count();
        });

        $('#addnForm .price_addon').off();
        $('#addnForm .price_addon').change(function ()
        {
            var price = $(this).val();
            console.log(price);
            {
                price = parseFloat(price).toFixed(3);
                $(this).val(price);
            }

        });
        addon_count();
    }


    function addon_count()
    {
        jQuery('.addon_count').each(function (index, element) {
            var value = index;
            value++;
            var addon_count = '<strong>Addon ' + value + '</strong>';
            $(this).html(addon_count);
        });
    }
</script>
@endpush
