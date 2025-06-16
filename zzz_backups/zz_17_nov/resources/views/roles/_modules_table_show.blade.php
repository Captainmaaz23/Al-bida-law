<div class="row mt-3">
    <div class="col-md-12">
        <strong>{{ $title }}</strong>
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-12">                    
        <table class="table table-striped table-hover">
            <thead>
                <tr role="row" class="heading">
                    <th style="width:22%">Module</th>
                    <th style="width:13%">Listing</th>
                    <th style="width:13%">Add</th>
                    <th style="width:13%">Update</th>
                    <th style="width:13%">Details</th>
                    <th style="width:13%">Status</th>  
                    <th style="width:13%">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $Module)
                @php
                    $Module_id = $Module->id;
                @endphp
                <tr role="row" class="heading">
                    <td>{{ ucwords($Module->module_name) }}</td>
                    @foreach(['list', 'add', 'edit', 'view', 'status', 'delete'] as $action)
                    <td>
                        @php
                            $status = ${"{$action}_array"}[$Module_id];
                            $btnClass = $status == 1 ? 'btn-success' : 'btn-danger';
                            $icon = $status == 1 ? 'fa-check' : 'fa-times';
                        @endphp
                        <button class="btn {{ $btnClass }} btn-sm">
                            <i class="fa {{ $icon }}"></i>
                        </button>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
