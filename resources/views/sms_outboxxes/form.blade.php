
<div class="form-group {{ $errors->has('sms_receiver_number') ? 'has-error' : '' }}">
    <label for="sms_receiver_number" class="col-md-2 control-label">Sms Receiver Number</label>
    <div class="col-md-10">
        <input class="form-control" name="sms_receiver_number" type="number" id="sms_receiver_number" value="{{ old('sms_receiver_number', optional($smsOutboxx)->sms_receiver_number) }}" placeholder="Enter sms receiver number here...">
        {!! $errors->first('sms_receiver_number', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('sms_content') ? 'has-error' : '' }}">
    <label for="sms_content" class="col-md-2 control-label">Sms Content</label>
    <div class="col-md-10">
        <input class="form-control" name="sms_content" type="text" id="sms_content" value="{{ old('sms_content', optional($smsOutboxx)->sms_content) }}" minlength="1" placeholder="Enter sms content here...">
        {!! $errors->first('sms_content', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('order_type') ? 'has-error' : '' }}">
    <label for="order_type" class="col-md-2 control-label">Order Type</label>
    <div class="col-md-10">
        <input class="form-control" name="order_type" type="text" id="order_type" value="{{ old('order_type', optional($smsOutboxx)->order_type) }}" minlength="1" placeholder="Enter order type here...">
        {!! $errors->first('order_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

