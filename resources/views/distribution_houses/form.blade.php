
<div class="form-group {{ $errors->has('zones_id') ? 'has-error' : '' }}">
    <label for="zones_id" class="col-md-2 control-label">Zones</label>
    <div class="col-md-10">
        <select class="form-control" id="zones_id" name="zones_id" required="true">
        	    <option value="" style="display: none;" {{ old('zones_id', optional($distributionHouse)->zones_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select zones</option>
        	@foreach ($zones as $key => $zone)
			    <option value="{{ $key }}" {{ old('zones_id', optional($distributionHouse)->zones_id) == $key ? 'selected' : '' }}>
			    	{{ $zone }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('zones_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('regions_id') ? 'has-error' : '' }}">
    <label for="regions_id" class="col-md-2 control-label">Regions</label>
    <div class="col-md-10">
        <select class="form-control" id="regions_id" name="regions_id" required="true">
        	    <option value="" style="display: none;" {{ old('regions_id', optional($distributionHouse)->regions_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select regions</option>
        	@foreach ($regions as $key => $region)
			    <option value="{{ $key }}" {{ old('regions_id', optional($distributionHouse)->regions_id) == $key ? 'selected' : '' }}>
			    	{{ $region }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('regions_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('territories_id') ? 'has-error' : '' }}">
    <label for="territories_id" class="col-md-2 control-label">Territories</label>
    <div class="col-md-10">
        <select class="form-control" id="territories_id" name="territories_id" required="true">
        	    <option value="" style="display: none;" {{ old('territories_id', optional($distributionHouse)->territories_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select territories</option>
        	@foreach ($territories as $key => $territory)
			    <option value="{{ $key }}" {{ old('territories_id', optional($distributionHouse)->territories_id) == $key ? 'selected' : '' }}>
			    	{{ $territory }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('territories_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('market_name') ? 'has-error' : '' }}">
    <label for="market_name" class="col-md-2 control-label">Market Name</label>
    <div class="col-md-10">
        <input class="form-control" name="market_name" type="text" id="market_name" value="{{ old('market_name', optional($distributionHouse)->market_name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter market name here...">
        {!! $errors->first('market_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('code') ? 'has-error' : '' }}">
    <label for="code" class="col-md-2 control-label">Code</label>
    <div class="col-md-10">
        <input class="form-control" name="code" type="text" id="code" value="{{ old('code', optional($distributionHouse)->code) }}" minlength="1" maxlength="255" required="true" placeholder="Enter code here...">
        {!! $errors->first('code', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('point_name') ? 'has-error' : '' }}">
    <label for="point_name" class="col-md-2 control-label">Point Name</label>
    <div class="col-md-10">
        <input class="form-control" name="point_name" type="text" id="point_name" value="{{ old('point_name', optional($distributionHouse)->point_name) }}" minlength="1" maxlength="255" required="true" placeholder="Enter point name here...">
        {!! $errors->first('point_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

