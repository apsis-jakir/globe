
<div class="form-group {{ $errors->has('sms_inbox_name') ? 'has-error' : '' }}">
    <label for="sms_inbox_name" class="col-md-2 control-label">Sms Inbox Name</label>
    <div class="col-md-10">
        <input class="form-control" name="sms_inbox_name" type="text" id="sms_inbox_name" value="{{ old('sms_inbox_name', optional($smsinbox)->sms_inbox_name) }}" maxlength="100" placeholder="Enter sms inbox name here...">
        {!! $errors->first('sms_inbox_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('transactionId') ? 'has-error' : '' }}">
    <label for="transactionId" class="col-md-2 control-label">Transaction Id</label>
    <div class="col-md-10">
        <input class="form-control" name="transactionId" type="text" id="transactionId" value="{{ old('transactionId', optional($smsinbox)->transactionId) }}" maxlength="30" placeholder="Enter transaction id here...">
        {!! $errors->first('transactionId', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('sender') ? 'has-error' : '' }}">
    <label for="sender" class="col-md-2 control-label">Sender</label>
    <div class="col-md-10">
        <input class="form-control" name="sender" type="text" id="sender" value="{{ old('sender', optional($smsinbox)->sender) }}" maxlength="255" placeholder="Enter sender here...">
        {!! $errors->first('sender', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('sms_status') ? 'has-error' : '' }}">
    <label for="sms_status" class="col-md-2 control-label">Sms Status</label>
    <div class="col-md-10">
        <input class="form-control" name="sms_status" type="text" id="sms_status" value="{{ old('sms_status', optional($smsinbox)->sms_status) }}" maxlength="100" placeholder="Enter sms status here...">
        {!! $errors->first('sms_status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('sms_received') ? 'has-error' : '' }}">
    <label for="sms_received" class="col-md-2 control-label">Sms Received</label>
    <div class="col-md-10">
        <input class="form-control" name="sms_received" type="text" id="sms_received" value="{{ old('sms_received', optional($smsinbox)->sms_received) }}" placeholder="Enter sms received here...">
        {!! $errors->first('sms_received', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('replied_at') ? 'has-error' : '' }}">
    <label for="replied_at" class="col-md-2 control-label">Replied At</label>
    <div class="col-md-10">
        <input class="form-control" name="replied_at" type="text" id="replied_at" value="{{ old('replied_at', optional($smsinbox)->replied_at) }}" placeholder="Enter replied at here...">
        {!! $errors->first('replied_at', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('sms_content') ? 'has-error' : '' }}">
    <label for="sms_content" class="col-md-2 control-label">Sms Content</label>
    <div class="col-md-10">
        <textarea class="form-control" name="sms_content" cols="50" rows="10" id="sms_content" required="true" placeholder="Enter sms content here...">{{ old('sms_content', optional($smsinbox)->sms_content) }}</textarea>
        {!! $errors->first('sms_content', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('created_by') ? 'has-error' : '' }}">
    <label for="created_by" class="col-md-2 control-label">Created By</label>
    <div class="col-md-10">
        <select class="form-control" id="created_by" name="created_by">
        	    <option value="" style="display: none;" {{ old('created_by', optional($smsinbox)->created_by ?: '') == '' ? 'selected' : '' }} disabled selected>Select created by</option>
        	@foreach ($creators as $key => $creator)
			    <option value="{{ $key }}" {{ old('created_by', optional($smsinbox)->created_by) == $key ? 'selected' : '' }}>
			    	{{ $creator }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('created_by', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('updated_by') ? 'has-error' : '' }}">
    <label for="updated_by" class="col-md-2 control-label">Updated By</label>
    <div class="col-md-10">
        <select class="form-control" id="updated_by" name="updated_by">
        	    <option value="" style="display: none;" {{ old('updated_by', optional($smsinbox)->updated_by ?: '') == '' ? 'selected' : '' }} disabled selected>Select updated by</option>
        	@foreach ($updaters as $key => $updater)
			    <option value="{{ $key }}" {{ old('updated_by', optional($smsinbox)->updated_by) == $key ? 'selected' : '' }}>
			    	{{ $updater }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('updated_by', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('is_active') ? 'has-error' : '' }}">
    <label for="is_active" class="col-md-2 control-label">Is Active</label>
    <div class="col-md-10">
        <div class="checkbox">
            <label for="is_active_1">
            	<input id="is_active_1" class="" name="is_active" type="checkbox" value="1" {{ old('is_active', optional($smsinbox)->is_active) == '1' ? 'checked' : '' }}>
                Yes
            </label>
        </div>

        {!! $errors->first('is_active', '<p class="help-block">:message</p>') !!}
    </div>
</div>

