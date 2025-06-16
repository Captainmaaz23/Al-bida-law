<link rel="stylesheet" href="{{ asset_url('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset_url('css/custom_select2.css') }}">
<script src="{{ asset_url('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
jQuery(document).ready(function () {
    initSelect2('#rest_select2', "Select a Restaurant");
    initSelect2('.rest_select2', "Select a Restaurant");
    initSelect2('#cat_select2', "Select Multiple Categories");
    initSelect2('.cat_select2', "Select Multiple Categories");
    initSelect2('#menu_select2', "Select a Menu");
    initSelect2('.menu_select2', "Select a Menu");
    initSelect2('#item_select2', "Select an Item");
    initSelect2('.item_select2', "Select an Item");
});

function initSelect2(selector, placeholder) {
    if ($(selector).length) {
        $(selector).select2({
            width: '100%',
            placeholder: placeholder,
            allowClear: true
        });
    }
}
</script>
