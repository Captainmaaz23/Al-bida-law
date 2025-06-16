
<div class="form-group col-sm-12">
    <label for="slected_menus">Select Categories:</label>
    <select name="cat_idd" class="form-control cat_idd">
        <option value="" selected disabled>select</option>
        @foreach ($AllCategories as $category)
            <option value="{{ $category->id }}" {{ $category->id == -1 ? 'selected' : '' }}>{{ $category->title }}</option>
        @endforeach
    </select>

</div>

<div class="form-group col-sm-12">
    <label for="title">Title [ EN ]:</label>
    <input type="text" name="title" class="form-control" value="">
</div>
<div class="form-group col-sm-12">
    <label for="ar_title">Title [ AR ]:</label>
    <input type="text" name="ar_title" class="form-control" value="">
</div>

<div class="col-sm-6 "> 
    <input type="hidden" name="rest_idd" value="<?php echo $Model_Data->id; ?>" class="form-control rest_idd">
</div>    


<div class="mt-4 mb-3 row">
    <div class="col-sm-12 text-right">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="#" class='btn btn-outline-dark' id="btnclose" data-dismiss="modal">
            Cancel
        </a>                        
    </div>
</div>