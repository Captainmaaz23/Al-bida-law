<div class="form-group col-sm-6">
    <label for="user_id">User Id:</label>
    <input type="number" name="user_id" class="form-control">
</div>

<div class="form-group col-sm-6">
    <label for="total">Total:</label>
    <input type="text" name="total" class="form-control" value="">
</div>

<div class="form-group col-sm-6">
    <label for="store_id">Store Id:</label>
    <input type="number" name="store_id" class="form-control">
</div>

<div class="form-group col-sm-6">
    <label for="status">Status:</label>
    <input type="number" name="status" class="form-control">
</div>

<div class="form-group col-sm-12">
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">Cancel</a>
</div>
