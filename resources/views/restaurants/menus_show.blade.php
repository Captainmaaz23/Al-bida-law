<div class="modal" id="menusModal" tabindex="-1" role="dialog" aria-labelledby="menusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed mb-0">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Change Menus Order</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option btn_close_menu_modal" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="block-content fs-sm">
                    @if ($menus->isNotEmpty())
                        <form action="{{ route('restaurants.menus.order', $Model_Data->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr role="row" class="heading">
                                            <th>#</th>
                                            <th>Menus</th>
                                            <th>Items</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($menus as $index => $record)
                                            <tr>
                                                <td>
                                                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                                                    <span class="spncount">{{ $index + 1 }}</span>
                                                    <input type="hidden" name="menu_order[]" value="{{ $record->id }}" />
                                                </td>
                                                <td>{{ $record->title }}</td>
                                                <td>{{ $menu_items_array[$record->id] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 mb-3">
                                <hr />
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Save Menus Order</button>
                                    <button type="button" class="btn btn-outline-dark btn_close_menu_modal" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <p>No records found</p>
                            </div>
                        </div>
                    @endif
                </div>   
            </div>
        </div>
    </div>
</div>
