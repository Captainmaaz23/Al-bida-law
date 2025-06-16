@if($items->isNotEmpty())
    <div class="table-responsive"> 
        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead>
                    <tr role="row" class="heading">                 
                        <th>#</th>                 
                        <th>Item</th>                                                   
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $record)
                        <tr>
                            <td>
                                <span class="ui-icon ui-icon-arrowthick-2-n-s"></span> 
                                <span class="spncount">{{ $index + 1 }}</span>
                                <input type="hidden" name="item_order[]" value="{{ $record->id }}" />
                            </td>
                            <td>
                                {!! $record->name !!}
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
            <a href="{{ route('menus.index') }}" class="btn btn-outline-dark">Cancel</a>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-sm-12">
            No records found
        </div>
    </div>
@endif