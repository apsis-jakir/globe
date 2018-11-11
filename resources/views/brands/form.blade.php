
<div class="form-group {{ $errors->has('categories_id') ? 'has-error' : '' }}">
    <label for="categories_id" class="col-md-2 control-label">Categories</label>
    <div class="col-md-10">
        <select class="form-control" id="categories_id" name="categories_id">
        	    <option value="" style="display: none;" {{ old('categories_id', optional($brand)->categories_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select categories</option>
        	@foreach ($categories as $key => $category)
			    <option value="{{ $key }}" {{ old('categories_id', optional($brand)->categories_id) == $key ? 'selected' : '' }}>
			    	{{ $category }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('categories_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('brand_name') ? 'has-error' : '' }}">
    <label for="brand_name" class="col-md-2 control-label">Brand Name</label>
    <div class="col-md-10">
        <input class="form-control" name="brand_name" type="text" id="brand_name" value="{{ old('brand_name', optional($brand)->brand_name) }}" maxlength="255" placeholder="Enter brand name here...">
        {!! $errors->first('brand_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('segment') ? 'has-error' : '' }}">
    <label for="segment" class="col-md-2 control-label">Segment</label>
    <div class="col-md-10">
        <input class="form-control" name="segment" type="text" id="segment" value="{{ old('segment', optional($brand)->segment) }}" maxlength="255" placeholder="Enter segment here...">
        {!! $errors->first('segment', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
    <label for="description" class="col-md-2 control-label">Description</label>
    <div class="col-md-10">
        <textarea class="form-control" name="description" cols="50" rows="10" id="description">{{ old('description', optional($brand)->description) }}</textarea>
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('is_active') ? 'has-error' : '' }}">
    <label for="is_active" class="col-md-2 control-label">Is Active</label>
    <div class="col-md-10">
        <div class="checkbox">
            <label for="is_active_1">
            	<input id="is_active_1" class="" name="is_active" type="checkbox" value="1" {{ old('is_active', optional($brand)->is_active) == '1' ? 'checked' : '' }}>
                Yes
            </label>
        </div>

        {!! $errors->first('is_active', '<p class="help-block">:message</p>') !!}
    </div>
</div>

