<div class="row form-group">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-4">
                <label for="field_1">Phone Number:</label>
            </div>
            <div class="col-sm-4">
                <input type="text" name="field_1" value="<?php echo $Model_Data_1->value; ?>" placeholder="" required class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_2">Email:</label>
            </div>
            <div class="col-sm-4">
                <input type="text" name="field_2" value="<?php echo $Model_Data_2->value; ?>" placeholder="" required class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_3">Website:</label>
            </div>
            <div class="col-sm-4">
                <input type="text" name="field_3" value="<?php echo $Model_Data_3->value; ?>" placeholder="" required class="form-control">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_4">VAT:</label>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="text" name="field_4" value="<?php echo $Model_Data_4->value; ?>" placeholder="" required class="form-control">
                    <span class="input-group-text">%</span>
                </div>                
            </div>
        </div>
        <?php /* ?><div class="row mt-3">
          <div class="col-sm-4">
          <label for="field_5">Order Declining Time:</label>
          </div>
          <div class="col-sm-4">
          <div class="input-group">
          <input type="text" name="field_5" value="<?php echo $Model_Data_5->value;?>" placeholder="" required class="form-control">
          <span class="input-group-text">Minutes</span>
          </div>
          </div>
          </div>
          <div class="row mt-3">
          <div class="col-sm-4">
          <label for="field_6">Order Declining Reason:</label>
          </div>
          <div class="col-sm-4">
          <div class="input-group">
          <input type="text" name="field_6" value="<?php echo $Model_Data_6->value;?>" placeholder="" required class="form-control">
          </div>
          </div>
          </div>
          <div class="row mt-3">
          <div class="col-sm-4">
          <label for="field_7">Order Collection Time:</label>
          </div>
          <div class="col-sm-4">
          <div class="input-group">
          <input type="text" name="field_7" value="<?php echo $Model_Data_7->value;?>" placeholder="" required class="form-control">
          <span class="input-group-text">Minutes</span>
          </div>
          </div>
          </div>
          <div class="row mt-3">
          <div class="col-sm-4">
          <label for="field_9">Maximum Fixed Discount Value:</label>
          </div>
          <div class="col-sm-4">
          <div class="input-group">
          <input type="text" name="field_9" value="<?php echo $Model_Data_9->value;?>" placeholder="" required class="form-control">
          <span class="input-group-text">%</span>
          </div>
          </div>
          </div>
          <div class="row mt-3">
          <div class="col-sm-4">
          <label for="field_10">Maximum Percentage Discount Value:</label>
          </div>
          <div class="col-sm-4">
          <div class="input-group">
          <input type="text" name="field_10" value="<?php echo $Model_Data_10->value;?>" placeholder="" required class="form-control">
          <span class="input-group-text">%</span>
          </div>
          </div>
          </div><?php */ ?>
        <div class="row mt-3">
            <div class="col-sm-4">
                <label for="field_11">New Order Notification Audio:</label>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <input type="file" name="field_11" class="form-control">
                </div>                
            </div>
            <div class="col-sm-4">
                <?php
                $file_path = 'audios/';
                $file = $Model_Data_11->value;
                if ($file == 'new_order.mp3') {
                    $image_path = 'defaults/';
                }
                $file_path .= $file;
                ?>
                <button type="button" class="vhide" id="play_audio" onclick="playSound('{{ uploads($file_path) }}');">Play</button>              
            </div>    
        </div>
        <div class="row mt-3">
            <div class="col-sm-4">
            </div>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('contact-details.index') }}" class="btn btn-outline-dark">Cancel</a>
            </div>
        </div>
    </div>
</div>

<script>
function playSound(url)
{
    const audio = new Audio(url);
    audio.play();
}
</script>