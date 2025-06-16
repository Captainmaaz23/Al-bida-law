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
                @foreach($modules as $count => $Module)
                    @php
                        $Module_id = $Module->id;
                    @endphp
                    <tr role="row" class="heading">
                        <td>{{ ucwords($Module->module_name) }}</td>
                        @foreach(['list' => 'mod_list', 'add' => 'mod_add', 'edit' => 'mod_edit', 'view' => 'mod_view', 'status' => 'mod_status', 'delete' => 'mod_delete'] as $action => $mod)
                            <td>
                                @if ($Module->$mod == 1)
                                    @php
                                        $is_mod = ${"{$action}_array"}[$Module_id];
                                        $field = "id_{$action}_" . $count;
                                        $classActive = $is_mod == 1 ? 'active' : 'notActive';
                                    @endphp
                                    <div class="btn-group radioBtn">
                                        <a class="btn btn-success btn-sm {{ $classActive }}" data-toggle="{{ $field }}" data-title="1">
                                            <i class="fa fa-check fa-lg"></i>
                                        </a>
                                        <a class="btn btn-danger btn-sm {{ $classActive === 'notActive' ? 'active' : 'notActive' }}" data-toggle="{{ $field }}" data-title="0">
                                            <i class="fa fa-times fa-lg"></i>
                                        </a>
                                    </div>
                                    <input type="hidden" name="{{ $action }}_module[{{ $count }}]" id="{{ $field }}" value="{{ $is_mod }}">
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
