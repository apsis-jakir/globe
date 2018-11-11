
<div class="form-group {{ $errors->has('sender') ? 'has-error' : '' }}">
    <label for="sender" class="col-md-2 control-label">Sender</label>
    <div class="col-md-10">
        <input class="form-control" name="sender" type="text" id="sender" value="{{ old('sender', optional($smsInbox)->sender) }}" minlength="1" maxlength="15" required="true" placeholder="Enter sender here...">
        {!! $errors->first('sender', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('sms_content') ? 'has-error' : '' }}">
    <label for="sms_content" class="col-md-2 control-label">SMS Content</label>
    <div class="col-md-10">
        <textarea class="form-control" name="sms_content" cols="50" rows="10" id="sms_content" required="true" placeholder="Enter SMS content here...">{{ old('sms_content', optional($smsInbox)->sms_content) }}</textarea>
        {!! $errors->first('sms_content', '<p class="help-block">:message</p>') !!}
    </div>
</div>

{{--<div class="form-group {{ $errors->has('sms_status') ? 'has-error' : '' }}">--}}
    {{--<label for="sms_status" class="col-md-2 control-label">Sms Status</label>--}}
    {{--<div class="col-md-10">--}}
        {{--<select class="form-control" id="sms_status" name="sms_status">--}}
        	    {{--<option value="" style="display: none;" {{ old('sms_status', optional($smsInbox)->sms_status ?: '') == '' ? 'selected' : '' }} disabled selected>Enter sms status here...</option>--}}
        	{{--@foreach (['Active' => 'Active',--}}
{{--'Inactive' => 'Inactive',--}}
{{--'Pending' => 'Pending',--}}
{{--'Replied' => 'Replied',--}}
{{--'Unread' => 'Unread'] as $key => $text)--}}
			    {{--<option value="{{ $key }}" {{ old('sms_status', optional($smsInbox)->sms_status) == $key ? 'selected' : '' }}>--}}
			    	{{--{{ $text }}--}}
			    {{--</option>--}}
			{{--@endforeach--}}
        {{--</select>--}}

        {{--{!! $errors->first('sms_status', '<p class="help-block">:message</p>') !!}--}}
    {{--</div>--}}
{{--</div>--}}

<div class="form-group {{ $errors->has('is_active') ? 'has-error' : '' }}">
    <label for="is_active" class="col-md-2 control-label">Is Active</label>
    <div class="col-md-10">
        <div class="checkbox">
            <label for="is_active_1">
            	<input id="is_active_1" class="" name="is_active" type="checkbox" value="1" {{ old('is_active', optional($smsInbox)->is_active) == '1' ? 'checked' : '' }}>
                Yes
            </label>
        </div>

        {!! $errors->first('is_active', '<p class="help-block">:message</p>') !!}
    </div>
</div>

