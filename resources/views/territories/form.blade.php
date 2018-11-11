
<div class="form-group {{ $errors->has('territory_name') ? 'has-error' : '' }}">
    <label for="territory_name" class="col-md-2 control-label">Territory Name</label>
    <div class="col-md-10">
        <input class="form-control" name="territory_name" type="text" id="territory_name" value="{{ old('territory_name', optional($territory)->territory_name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter territory name here...">
        {!! $errors->first('territory_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

