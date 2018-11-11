
<div class="form-group {{ $errors->has('images') ? 'has-error' : '' }}">
    <label for="images" class="col-md-2 control-label">Images</label>
    <div class="col-md-10">
        <div class="input-group uploaded-file-group">
            <label class="input-group-btn">
                <span class="btn btn-default">
                    Browse <input type="file" name="images" id="images" class="hidden">
                </span>
            </label>
            <input type="text" class="form-control uploaded-file-name" readonly>
        </div>

        @if (isset($fileUpload->images) && !empty($fileUpload->images))
            <div class="input-group input-width-input">
                <span class="input-group-addon">
                    <input type="checkbox" name="custom_delete_images" class="custom-delete-file" value="1" {{ old('custom_delete_images', '0') == '1' ? 'checked' : '' }}> Delete
                </span>

                <span class="input-group-addon custom-delete-file-name">
                    {{ basename($fileUpload->images) }}
                </span>
            </div>
        @endif
        {!! $errors->first('images', '<p class="help-block">:message</p>') !!}
    </div>
</div>

