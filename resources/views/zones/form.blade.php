
<div class="form-group {{ $errors->has('zone_name') ? 'has-error' : '' }}">
    <label for="zone_name" class="col-md-2 control-label">Zone Name</label>
    <div class="col-md-10">
        <input class="form-control" name="zone_name" type="text" id="zone_name" value="{{ old('zone_name', optional($zone)->zone_name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter zone name here...">
        {!! $errors->first('zone_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

