
<div class="form-group {{ $errors->has('category_name') ? 'has-error' : '' }}">
    <label for="category_name" class="col-md-2 control-label">Category Name</label>
    <div class="col-md-10">
        <input class="form-control" name="category_name" type="text" id="category_name" value="{{ old('category_name', optional($productType)->category_name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter category name here...">
        {!! $errors->first('category_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('is_active') ? 'has-error' : '' }}">
    <label for="is_active" class="col-md-2 control-label">Is Active</label>
    <div class="col-md-10">
        <div class="checkbox">
            <label for="is_active_1">
            	<input id="is_active_1" class="" name="is_active" type="checkbox" value="1" {{ old('is_active', optional($productType)->is_active) == '1' ? 'checked' : '' }}>
                Yes
            </label>
        </div>

        {!! $errors->first('is_active', '<p class="help-block">:message</p>') !!}
    </div>
</div>

