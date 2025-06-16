<div class="row form-group">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-4">
                <label for="field_1">Phone Number:</label>
            </div>
            <div class="col-sm-4">
                <input type="tel" id="field_1" name="field_1" value="{{ $Model_Data_1->value ?? null }}" placeholder="" required class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_2">Email:</label>
            </div>
            <div class="col-sm-4">
                <input type="email" id="field_2" name="field_2" value="{{ $Model_Data_2->value ?? null }}" placeholder="" required class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_3">Website:</label>
            </div>
            <div class="col-sm-4">
                <input type="url" id="field_3" name="field_3" value="{{ $Model_Data_3->value ?? null }}" placeholder="" required class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_4">GST:</label>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="text" id="field_4" name="field_4" value="{{ $Model_Data_4->value ?? null }}" placeholder="" required class="form-control">
                    <span class="input-group-text">%</span>
                </div>                
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_5">Service Charges:</label>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="text" id="field_5" name="field_5" value="{{ $Model_Data_5->value ?? null }}" placeholder="" required class="form-control">
                    <span class="input-group-text">%</span>
                </div>                
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_11">New Order Notification Audio:</label>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="file" id="field_11" name="field_11" class="form-control">
                </div>                
            </div>
            <div class="col-sm-4">
                <?php
                $file_path = 'audios/';
                $file = $Model_Data_11->value ?? null;
                $file_path .= ($file == 'new_order.mp3') ? 'defaults/' : '';
                $file_path .= $file;
                ?>
                <button type="button" onclick="playSound('{{ uploads($file_path) }}');">Play</button>              
            </div>    
        </div>
        <div class="row mt-3">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('general-settings.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>
</div>

<script>
    function playSound(url) {
        const audio = new Audio(url);
        audio.play();
    }
</script>