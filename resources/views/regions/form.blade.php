
<div class="form-group {{ $errors->has('region_name') ? 'has-error' : '' }}">
    <label for="region_name" class="col-md-2 control-label">Region Name</label>
    <div class="col-md-10">
        <input class="form-control" name="region_name" type="text" id="region_name" value="{{ old('region_name', optional($region)->region_name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter region name here...">
        {!! $errors->first('region_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

