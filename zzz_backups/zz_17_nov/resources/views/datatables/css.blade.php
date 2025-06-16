<link rel="stylesheet" href="{{ asset_url('bundles/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset_url('bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset_url('css/custom_datatable.css') }}">    
@php
$theme = Auth::user()?->theme ?? 'default';
$themeColors = [
'amethyst' => '#8451d6',
'flat' => '#44b3ab',
'city' => '#d65151',
'modern' => '#4cb6c7',
'smooth' => '#ff6c9d',
'default' => '#5179d6'
];
$buttonColor = $themeColors[$theme] ?? $themeColors['default'];
@endphp
<style>
    .buttons-html5 {
        color: #fff;
        background-color: <?php echo $buttonColor; ?>;
        border-color: <?php echo $buttonColor; ?>;
    }
</style>