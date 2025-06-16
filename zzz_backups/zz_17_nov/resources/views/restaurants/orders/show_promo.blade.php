<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="form-group col-sm-12">
                <p> <strong>Promo Code</strong> {{ $Model_Data->p_code }}</p>
            </div>
        </div>
        
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="name">Name [ English ]:</label>
                <p>{{ $Model_Data->p_name }}</p>
            </div>
            <div class="form-group col-sm-6">
                <label for="ar_name">Name [ Urdu ]:</label>
                <p>{{ $Model_Data->p_a_name }}</p>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <label for="amount">Promo Amount:</label>
                <p>{{ $Model_Data->promo_value }}</p>
            </div>

            <div class="form-group col-sm-6">
                <label for="created_at">Created At:</label>
                <p>{{ $Model_Data->created_at }}</p>
            </div>
        </div>
    </div>
</div>