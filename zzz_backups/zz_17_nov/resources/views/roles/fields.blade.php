<div class="row justify-content-center mt-2 mb-2">
    <div class="col-sm-8">
        <div class="form-group row">
            <label for="name" class="col-sm-4 col-form-label">Name:</label>
            <div class="col-sm-8">
                <input type="text" name="name" class="form-control" value="">
            </div>
        </div>
        
        <input type="hidden" name="guard_name" value="web">
        <input type="hidden" name="display_to" value="0">
        <?php /* ?><div class="row mt-3">
          <div class="col-sm-4">
          <label for="display">Display:</label>
          </div>
          <div class="col-sm-8">
          <select name="display_to" class="form-control">
          <option value="0">For Restaurant Users Only</option>
          <option value="1">For Admin Users Only</option>
          </select>

          </div>
          </div><?php */ ?>
        
        <div class="row mt-3">
            <div class="col-sm-8 offset-sm-4">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('roles.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>
</div>
