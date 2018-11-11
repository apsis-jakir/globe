
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($user)->name) }}" maxlength="255" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="col-md-2 control-label">Email</label>
    <div class="col-md-10">
        <input class="form-control" name="email" type="text" id="email" value="{{ old('email', optional($user)->email) }}" minlength="1" maxlength="255" required="true" placeholder="Enter email here...">
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password" class="col-md-2 control-label">Password</label>
    <div class="col-md-10">
        <input class="form-control" name="password" type="text" id="password" value="{{ old('password', optional($user)->password) }}" maxlength="255" placeholder="Enter password here...">
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('password_key') ? 'has-error' : '' }}">
    <label for="password_key" class="col-md-2 control-label">Password Key</label>
    <div class="col-md-10">
        <input class="form-control" name="password_key" type="text" id="password_key" value="{{ old('password_key', optional($user)->password_key) }}" maxlength="200" placeholder="Enter password key here...">
        {!! $errors->first('password_key', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('password_expire_days') ? 'has-error' : '' }}">
    <label for="password_expire_days" class="col-md-2 control-label">Password Expire Days</label>
    <div class="col-md-10">
        <input class="form-control" name="password_expire_days" type="number" id="password_expire_days" value="{{ old('password_expire_days', optional($user)->password_expire_days) }}" min="-2147483648" max="2147483647" placeholder="Enter password expire days here...">
        {!! $errors->first('password_expire_days', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
    <label for="last_name" class="col-md-2 control-label">Last Name</label>
    <div class="col-md-10">
        <input class="form-control" name="last_name" type="text" id="last_name" value="{{ old('last_name', optional($user)->last_name) }}" maxlength="100" placeholder="Enter last name here...">
        {!! $errors->first('last_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
    <label for="mobile" class="col-md-2 control-label">Mobile</label>
    <div class="col-md-10">
        <input class="form-control" name="mobile" type="text" id="mobile" value="{{ old('mobile', optional($user)->mobile) }}" maxlength="11" placeholder="Enter mobile here...">
        {!! $errors->first('mobile', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('home_telephone') ? 'has-error' : '' }}">
    <label for="home_telephone" class="col-md-2 control-label">Home Telephone</label>
    <div class="col-md-10">
        <input class="form-control" name="home_telephone" type="text" id="home_telephone" value="{{ old('home_telephone', optional($user)->home_telephone) }}" maxlength="20" placeholder="Enter home telephone here...">
        {!! $errors->first('home_telephone', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
    <label for="username" class="col-md-2 control-label">Username</label>
    <div class="col-md-10">
        <input class="form-control" name="username" type="text" id="username" value="{{ old('username', optional($user)->username) }}" maxlength="15" placeholder="Enter username here...">
        {!! $errors->first('username', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('secret_question_1') ? 'has-error' : '' }}">
    <label for="secret_question_1" class="col-md-2 control-label">Secret Question 1</label>
    <div class="col-md-10">
        <input class="form-control" name="secret_question_1" type="text" id="secret_question_1" value="{{ old('secret_question_1', optional($user)->secret_question_1) }}" maxlength="100" placeholder="Enter secret question 1 here...">
        {!! $errors->first('secret_question_1', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('secret_question_2') ? 'has-error' : '' }}">
    <label for="secret_question_2" class="col-md-2 control-label">Secret Question 2</label>
    <div class="col-md-10">
        <input class="form-control" name="secret_question_2" type="text" id="secret_question_2" value="{{ old('secret_question_2', optional($user)->secret_question_2) }}" maxlength="100" placeholder="Enter secret question 2 here...">
        {!! $errors->first('secret_question_2', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('secret_question_ans_1') ? 'has-error' : '' }}">
    <label for="secret_question_ans_1" class="col-md-2 control-label">Secret Question Ans 1</label>
    <div class="col-md-10">
        <input class="form-control" name="secret_question_ans_1" type="text" id="secret_question_ans_1" value="{{ old('secret_question_ans_1', optional($user)->secret_question_ans_1) }}" maxlength="100" placeholder="Enter secret question ans 1 here...">
        {!! $errors->first('secret_question_ans_1', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('secret_question_ans_2') ? 'has-error' : '' }}">
    <label for="secret_question_ans_2" class="col-md-2 control-label">Secret Question Ans 2</label>
    <div class="col-md-10">
        <input class="form-control" name="secret_question_ans_2" type="text" id="secret_question_ans_2" value="{{ old('secret_question_ans_2', optional($user)->secret_question_ans_2) }}" maxlength="100" placeholder="Enter secret question ans 2 here...">
        {!! $errors->first('secret_question_ans_2', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('identification_type_id') ? 'has-error' : '' }}">
    <label for="identification_type_id" class="col-md-2 control-label">Identification Type</label>
    <div class="col-md-10">
        <select class="form-control" id="identification_type_id" name="identification_type_id">
        	    <option value="" style="display: none;" {{ old('identification_type_id', optional($user)->identification_type_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select identification type</option>
        	@foreach ($identificationTypes as $key => $identificationType)
			    <option value="{{ $key }}" {{ old('identification_type_id', optional($user)->identification_type_id) == $key ? 'selected' : '' }}>
			    	{{ $identificationType }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('identification_type_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('identification_number') ? 'has-error' : '' }}">
    <label for="identification_number" class="col-md-2 control-label">Identification Number</label>
    <div class="col-md-10">
        <input class="form-control" name="identification_number" type="text" id="identification_number" value="{{ old('identification_number', optional($user)->identification_number) }}" min="0" max="30" placeholder="Enter identification number here...">
        {!! $errors->first('identification_number', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('identification_expire_date') ? 'has-error' : '' }}">
    <label for="identification_expire_date" class="col-md-2 control-label">Identification Expire Date</label>
    <div class="col-md-10">
        <input class="form-control" name="identification_expire_date" type="text" id="identification_expire_date" value="{{ old('identification_expire_date', optional($user)->identification_expire_date) }}" placeholder="Enter identification expire date here...">
        {!! $errors->first('identification_expire_date', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
    <label for="date_of_birth" class="col-md-2 control-label">Date Of Birth</label>
    <div class="col-md-10">
        <input class="form-control" name="date_of_birth" type="text" id="date_of_birth" value="{{ old('date_of_birth', optional($user)->date_of_birth) }}" placeholder="Enter date of birth here...">
        {!! $errors->first('date_of_birth', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
    <label for="gender" class="col-md-2 control-label">Gender</label>
    <div class="col-md-10">
        <input class="form-control" name="gender" type="text" id="gender" value="{{ old('gender', optional($user)->gender) }}" maxlength="500" placeholder="Enter gender here...">
        {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('religion_id') ? 'has-error' : '' }}">
    <label for="religion_id" class="col-md-2 control-label">Religion</label>
    <div class="col-md-10">
        <select class="form-control" id="religion_id" name="religion_id">
        	    <option value="" style="display: none;" {{ old('religion_id', optional($user)->religion_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select religion</option>
        	@foreach ($religions as $key => $religion)
			    <option value="{{ $key }}" {{ old('religion_id', optional($user)->religion_id) == $key ? 'selected' : '' }}>
			    	{{ $religion }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('religion_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('father_name') ? 'has-error' : '' }}">
    <label for="father_name" class="col-md-2 control-label">Father Name</label>
    <div class="col-md-10">
        <input class="form-control" name="father_name" type="text" id="father_name" value="{{ old('father_name', optional($user)->father_name) }}" maxlength="100" placeholder="Enter father name here...">
        {!! $errors->first('father_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('father_occupation_id') ? 'has-error' : '' }}">
    <label for="father_occupation_id" class="col-md-2 control-label">Father Occupation</label>
    <div class="col-md-10">
        <select class="form-control" id="father_occupation_id" name="father_occupation_id">
        	    <option value="" style="display: none;" {{ old('father_occupation_id', optional($user)->father_occupation_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select father occupation</option>
        	@foreach ($fatherOccupations as $key => $fatherOccupation)
			    <option value="{{ $key }}" {{ old('father_occupation_id', optional($user)->father_occupation_id) == $key ? 'selected' : '' }}>
			    	{{ $fatherOccupation }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('father_occupation_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('mother_name') ? 'has-error' : '' }}">
    <label for="mother_name" class="col-md-2 control-label">Mother Name</label>
    <div class="col-md-10">
        <input class="form-control" name="mother_name" type="text" id="mother_name" value="{{ old('mother_name', optional($user)->mother_name) }}" maxlength="100" placeholder="Enter mother name here...">
        {!! $errors->first('mother_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('mother_occupation_id') ? 'has-error' : '' }}">
    <label for="mother_occupation_id" class="col-md-2 control-label">Mother Occupation</label>
    <div class="col-md-10">
        <select class="form-control" id="mother_occupation_id" name="mother_occupation_id">
        	    <option value="" style="display: none;" {{ old('mother_occupation_id', optional($user)->mother_occupation_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select mother occupation</option>
        	@foreach ($motherOccupations as $key => $motherOccupation)
			    <option value="{{ $key }}" {{ old('mother_occupation_id', optional($user)->mother_occupation_id) == $key ? 'selected' : '' }}>
			    	{{ $motherOccupation }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('mother_occupation_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('bank_account_number') ? 'has-error' : '' }}">
    <label for="bank_account_number" class="col-md-2 control-label">Bank Account Number</label>
    <div class="col-md-10">
        <input class="form-control" name="bank_account_number" type="text" id="bank_account_number" value="{{ old('bank_account_number', optional($user)->bank_account_number) }}" min="0" max="30" placeholder="Enter bank account number here...">
        {!! $errors->first('bank_account_number', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('bank_id') ? 'has-error' : '' }}">
    <label for="bank_id" class="col-md-2 control-label">Bank</label>
    <div class="col-md-10">
        <select class="form-control" id="bank_id" name="bank_id">
        	    <option value="" style="display: none;" {{ old('bank_id', optional($user)->bank_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select bank</option>
        	@foreach ($banks as $key => $bank)
			    <option value="{{ $key }}" {{ old('bank_id', optional($user)->bank_id) == $key ? 'selected' : '' }}>
			    	{{ $bank }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('bank_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('bank_branch') ? 'has-error' : '' }}">
    <label for="bank_branch" class="col-md-2 control-label">Bank Branch</label>
    <div class="col-md-10">
        <input class="form-control" name="bank_branch" type="text" id="bank_branch" value="{{ old('bank_branch', optional($user)->bank_branch) }}" maxlength="100" placeholder="Enter bank branch here...">
        {!! $errors->first('bank_branch', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('last_login_date_time') ? 'has-error' : '' }}">
    <label for="last_login_date_time" class="col-md-2 control-label">Last Login Date Time</label>
    <div class="col-md-10">
        <input class="form-control" name="last_login_date_time" type="text" id="last_login_date_time" value="{{ old('last_login_date_time', optional($user)->last_login_date_time) }}" placeholder="Enter last login date time here...">
        {!! $errors->first('last_login_date_time', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('default_module_id') ? 'has-error' : '' }}">
    <label for="default_module_id" class="col-md-2 control-label">Default Module</label>
    <div class="col-md-10">
        <select class="form-control" id="default_module_id" name="default_module_id">
        	    <option value="" style="display: none;" {{ old('default_module_id', optional($user)->default_module_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select default module</option>
        	@foreach ($defaultModules as $key => $defaultModule)
			    <option value="{{ $key }}" {{ old('default_module_id', optional($user)->default_module_id) == $key ? 'selected' : '' }}>
			    	{{ $defaultModule }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('default_module_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('user_image') ? 'has-error' : '' }}">
    <label for="user_image" class="col-md-2 control-label">User Image</label>
    <div class="col-md-10">
        <input class="form-control" name="user_image" type="text" id="user_image" value="{{ old('user_image', optional($user)->user_image) }}" min="0" max="100" placeholder="Enter user image here...">
        {!! $errors->first('user_image', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
    <label for="address" class="col-md-2 control-label">Address</label>
    <div class="col-md-10">
        <input class="form-control" name="address" type="text" id="address" value="{{ old('address', optional($user)->address) }}" maxlength="100" placeholder="Enter address here...">
        {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('is_reliever') ? 'has-error' : '' }}">
    <label for="is_reliever" class="col-md-2 control-label">Is Reliever</label>
    <div class="col-md-10">
        <input class="form-control" name="is_reliever" type="text" id="is_reliever" value="{{ old('is_reliever', optional($user)->is_reliever) }}">
        {!! $errors->first('is_reliever', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('reliever_to') ? 'has-error' : '' }}">
    <label for="reliever_to" class="col-md-2 control-label">Reliever To</label>
    <div class="col-md-10">
        <input class="form-control" name="reliever_to" type="number" id="reliever_to" value="{{ old('reliever_to', optional($user)->reliever_to) }}" min="-2147483648" max="2147483647" placeholder="Enter reliever to here...">
        {!! $errors->first('reliever_to', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('reliever_start_datetime') ? 'has-error' : '' }}">
    <label for="reliever_start_datetime" class="col-md-2 control-label">Reliever Start Datetime</label>
    <div class="col-md-10">
        <input class="form-control" name="reliever_start_datetime" type="text" id="reliever_start_datetime" value="{{ old('reliever_start_datetime', optional($user)->reliever_start_datetime) }}" placeholder="Enter reliever start datetime here...">
        {!! $errors->first('reliever_start_datetime', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('reliever_end_datetime') ? 'has-error' : '' }}">
    <label for="reliever_end_datetime" class="col-md-2 control-label">Reliever End Datetime</label>
    <div class="col-md-10">
        <input class="form-control" name="reliever_end_datetime" type="text" id="reliever_end_datetime" value="{{ old('reliever_end_datetime', optional($user)->reliever_end_datetime) }}" placeholder="Enter reliever end datetime here...">
        {!! $errors->first('reliever_end_datetime', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('line_manager_id') ? 'has-error' : '' }}">
    <label for="line_manager_id" class="col-md-2 control-label">Line Manager</label>
    <div class="col-md-10">
        <select class="form-control" id="line_manager_id" name="line_manager_id">
        	    <option value="" style="display: none;" {{ old('line_manager_id', optional($user)->line_manager_id ?: '') == '' ? 'selected' : '' }} disabled selected>Enter line manager here...</option>
        	@foreach ($lineManagers as $key => $lineManager)
			    <option value="{{ $key }}" {{ old('line_manager_id', optional($user)->line_manager_id) == $key ? 'selected' : '' }}>
			    	{{ $lineManager }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('line_manager_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('designation_id') ? 'has-error' : '' }}">
    <label for="designation_id" class="col-md-2 control-label">Designation</label>
    <div class="col-md-10">
        <select class="form-control" id="designation_id" name="designation_id">
        	    <option value="" style="display: none;" {{ old('designation_id', optional($user)->designation_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select designation</option>
        	@foreach ($designations as $key => $designation)
			    <option value="{{ $key }}" {{ old('designation_id', optional($user)->designation_id) == $key ? 'selected' : '' }}>
			    	{{ $designation }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('designation_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('department_id') ? 'has-error' : '' }}">
    <label for="department_id" class="col-md-2 control-label">Department</label>
    <div class="col-md-10">
        <select class="form-control" id="department_id" name="department_id">
        	    <option value="" style="display: none;" {{ old('department_id', optional($user)->department_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select department</option>
        	@foreach ($departments as $key => $department)
			    <option value="{{ $key }}" {{ old('department_id', optional($user)->department_id) == $key ? 'selected' : '' }}>
			    	{{ $department }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('department_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('location_id') ? 'has-error' : '' }}">
    <label for="location_id" class="col-md-2 control-label">Location</label>
    <div class="col-md-10">
        <select class="form-control" id="location_id" name="location_id">
        	    <option value="" style="display: none;" {{ old('location_id', optional($user)->location_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select location</option>
        	@foreach ($locations as $key => $location)
			    <option value="{{ $key }}" {{ old('location_id', optional($user)->location_id) == $key ? 'selected' : '' }}>
			    	{{ $location }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('location_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('default_vehicle_id') ? 'has-error' : '' }}">
    <label for="default_vehicle_id" class="col-md-2 control-label">Default Vehicle</label>
    <div class="col-md-10">
        <select class="form-control" id="default_vehicle_id" name="default_vehicle_id">
        	    <option value="" style="display: none;" {{ old('default_vehicle_id', optional($user)->default_vehicle_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select default vehicle</option>
        	@foreach ($defaultVehicles as $key => $defaultVehicle)
			    <option value="{{ $key }}" {{ old('default_vehicle_id', optional($user)->default_vehicle_id) == $key ? 'selected' : '' }}>
			    	{{ $defaultVehicle }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('default_vehicle_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('default_url') ? 'has-error' : '' }}">
    <label for="default_url" class="col-md-2 control-label">Default Url</label>
    <div class="col-md-10">
        <input class="form-control" name="default_url" type="text" id="default_url" value="{{ old('default_url', optional($user)->default_url) }}" maxlength="500" placeholder="Enter default url here...">
        {!! $errors->first('default_url', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('default_language_id') ? 'has-error' : '' }}">
    <label for="default_language_id" class="col-md-2 control-label">Default Language</label>
    <div class="col-md-10">
        <select class="form-control" id="default_language_id" name="default_language_id">
        	    <option value="" style="display: none;" {{ old('default_language_id', optional($user)->default_language_id ?: '') == '' ? 'selected' : '' }} disabled selected>Enter default language here...</option>
        	@foreach ($defaultLanguages as $key => $defaultLanguage)
			    <option value="{{ $key }}" {{ old('default_language_id', optional($user)->default_language_id) == $key ? 'selected' : '' }}>
			    	{{ $defaultLanguage }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('default_language_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('joining_date') ? 'has-error' : '' }}">
    <label for="joining_date" class="col-md-2 control-label">Joining Date</label>
    <div class="col-md-10">
        <input class="form-control" name="joining_date" type="text" id="joining_date" value="{{ old('joining_date', optional($user)->joining_date) }}" placeholder="Enter joining date here...">
        {!! $errors->first('joining_date', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('emergency_contact_person_name') ? 'has-error' : '' }}">
    <label for="emergency_contact_person_name" class="col-md-2 control-label">Emergency Contact Person Name</label>
    <div class="col-md-10">
        <input class="form-control" name="emergency_contact_person_name" type="text" id="emergency_contact_person_name" value="{{ old('emergency_contact_person_name', optional($user)->emergency_contact_person_name) }}" maxlength="100" placeholder="Enter emergency contact person name here...">
        {!! $errors->first('emergency_contact_person_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('emergency_contact_relation') ? 'has-error' : '' }}">
    <label for="emergency_contact_relation" class="col-md-2 control-label">Emergency Contact Relation</label>
    <div class="col-md-10">
        <input class="form-control" name="emergency_contact_relation" type="text" id="emergency_contact_relation" value="{{ old('emergency_contact_relation', optional($user)->emergency_contact_relation) }}" maxlength="30" placeholder="Enter emergency contact relation here...">
        {!! $errors->first('emergency_contact_relation', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('emergency_contact_number') ? 'has-error' : '' }}">
    <label for="emergency_contact_number" class="col-md-2 control-label">Emergency Contact Number</label>
    <div class="col-md-10">
        <input class="form-control" name="emergency_contact_number" type="text" id="emergency_contact_number" value="{{ old('emergency_contact_number', optional($user)->emergency_contact_number) }}" min="0" max="20" placeholder="Enter emergency contact number here...">
        {!! $errors->first('emergency_contact_number', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('remember_token') ? 'has-error' : '' }}">
    <label for="remember_token" class="col-md-2 control-label">Remember Token</label>
    <div class="col-md-10">
        <input class="form-control" name="remember_token" type="text" id="remember_token" value="{{ old('remember_token', optional($user)->remember_token) }}" maxlength="100" placeholder="Enter remember token here...">
        {!! $errors->first('remember_token', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('territories_id') ? 'has-error' : '' }}">
    <label for="territories_id" class="col-md-2 control-label">Territories</label>
    <div class="col-md-10">
        <select class="form-control" id="territories_id" name="territories_id" required="true">
        	    <option value="" style="display: none;" {{ old('territories_id', optional($user)->territories_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select territories</option>
        	@foreach ($territories as $key => $territory)
			    <option value="{{ $key }}" {{ old('territories_id', optional($user)->territories_id) == $key ? 'selected' : '' }}>
			    	{{ $territory }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('territories_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('distribution_houses_id') ? 'has-error' : '' }}">
    <label for="distribution_houses_id" class="col-md-2 control-label">Distribution Houses</label>
    <div class="col-md-10">
        <select class="form-control" id="distribution_houses_id" name="distribution_houses_id" required="true">
        	    <option value="" style="display: none;" {{ old('distribution_houses_id', optional($user)->distribution_houses_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select distribution houses</option>
        	@foreach ($distributionHouses as $key => $distributionHouse)
			    <option value="{{ $key }}" {{ old('distribution_houses_id', optional($user)->distribution_houses_id) == $key ? 'selected' : '' }}>
			    	{{ $distributionHouse }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('distribution_houses_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('rfu1') ? 'has-error' : '' }}">
    <label for="rfu1" class="col-md-2 control-label">Rfu1</label>
    <div class="col-md-10">
        <input class="form-control" name="rfu1" type="text" id="rfu1" value="{{ old('rfu1', optional($user)->rfu1) }}" maxlength="255" placeholder="Enter rfu1 here...">
        {!! $errors->first('rfu1', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('rfu2') ? 'has-error' : '' }}">
    <label for="rfu2" class="col-md-2 control-label">Rfu2</label>
    <div class="col-md-10">
        <input class="form-control" name="rfu2" type="text" id="rfu2" value="{{ old('rfu2', optional($user)->rfu2) }}" maxlength="255" placeholder="Enter rfu2 here...">
        {!! $errors->first('rfu2', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('zones_id') ? 'has-error' : '' }}">
    <label for="zones_id" class="col-md-2 control-label">Zones</label>
    <div class="col-md-10">
        <select class="form-control" id="zones_id" name="zones_id" required="true">
        	    <option value="" style="display: none;" {{ old('zones_id', optional($user)->zones_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select zones</option>
        	@foreach ($zones as $key => $zone)
			    <option value="{{ $key }}" {{ old('zones_id', optional($user)->zones_id) == $key ? 'selected' : '' }}>
			    	{{ $zone }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('zones_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('created_by') ? 'has-error' : '' }}">
    <label for="created_by" class="col-md-2 control-label">Created By</label>
    <div class="col-md-10">
        <select class="form-control" id="created_by" name="created_by">
        	    <option value="" style="display: none;" {{ old('created_by', optional($user)->created_by ?: '') == '' ? 'selected' : '' }} disabled selected>Select created by</option>
        	@foreach ($creators as $key => $creator)
			    <option value="{{ $key }}" {{ old('created_by', optional($user)->created_by) == $key ? 'selected' : '' }}>
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
        	    <option value="" style="display: none;" {{ old('updated_by', optional($user)->updated_by ?: '') == '' ? 'selected' : '' }} disabled selected>Select updated by</option>
        	@foreach ($updaters as $key => $updater)
			    <option value="{{ $key }}" {{ old('updated_by', optional($user)->updated_by) == $key ? 'selected' : '' }}>
			    	{{ $updater }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('updated_by', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
    <label for="status" class="col-md-2 control-label">Status</label>
    <div class="col-md-10">
        <input class="form-control" name="status" type="text" id="status" value="{{ old('status', optional($user)->status) }}" maxlength="500" placeholder="Enter status here...">
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

