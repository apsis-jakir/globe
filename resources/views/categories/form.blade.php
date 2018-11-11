
<div class="form-group {{ $errors->has('category_name') ? 'has-error' : '' }}">
    <label for="category_name" class="col-md-2 control-label">Category Name</label>
    <div class="col-md-10">
        <input class="form-control" name="category_name" type="text" id="category_name" value="{{ old('category_name', optional($category)->category_name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter category name here...">
        {!! $errors->first('category_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('category_description') ? 'has-error' : '' }}">
    <label for="category_description" class="col-md-2 control-label">Category Description</label>
    <div class="col-md-10">
        <textarea class="form-control" name="category_description" cols="50" rows="10" id="category_description" placeholder="Enter category description here...">{{ old('category_description', optional($category)->category_description) }}</textarea>
        {!! $errors->first('category_description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

