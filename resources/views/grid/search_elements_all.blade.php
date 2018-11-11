@php
$starMark = '<span style="color:red; font-weight:bold">*</span>';
@endphp
<div class="col-lg-12">
    @if(isset($searchAreaOption['zone']) && $searchAreaOption['zone'] == 1)
        <div class="col-lg-4 form-group">
            <label>Zone {!! (@$mendatory['zone'])?'':$starMark !!}</label>
            {!! dropdownList(1,@$post_data,@$mendatory['zone']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['region']) && $searchAreaOption['region'] == 1)
        <div class="col-lg-4 form-group">
            <label>Region {!! (@$mendatory['region'])?'':$starMark !!}</label>
            {!! dropdownList(2,@$post_data,@$mendatory['region']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['territory']) && $searchAreaOption['territory'] == 1)
        <div class="col-lg-4 form-group">
            <label>Territory {!! (@$mendatory['territory'])?'':$starMark !!}</label>
            {!! dropdownList(3,@$post_data,@$mendatory['territory']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['house']) && $searchAreaOption['house'] == 1)
        <div class="col-lg-4 form-group">
            <label>House {!! (@$mendatory['house'])?'':$starMark !!}</label>
            {!! dropdownList(4,@$post_data,@$mendatory['house']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['house_single']) && $searchAreaOption['house_single'] == 1)
        <div class="col-lg-4 form-group">
            <label>House {!! (@$mendatory['house'])?'':$starMark !!}</label>
            {!! dropdownList(10,@$post_data,@$mendatory['house']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['aso']) && $searchAreaOption['aso'] == 1)
        <div class="col-lg-4 form-group">
            <label>ASO/SO {!! (@$mendatory['aso'])?'':$starMark !!}</label>
            {!! dropdownList(8,@$post_data,@$mendatory['aso']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['route']) && $searchAreaOption['route'] == 1)
        <div class="col-lg-4 form-group">
            <label>Route {!! (@$mendatory['route'])?'':$starMark !!}</label>
            {!! dropdownList(9,@$post_data,@$mendatory['route']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['category']) && $searchAreaOption['category'] == 1)
        <div class="col-lg-4 form-group">
            <label>Category {!! (@$mendatory['category'])?'':$starMark !!}</label>
            {!! dropdownList(5,@$post_data,@$mendatory['category']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['brand']) && $searchAreaOption['brand'] == 1)
        <div class="col-lg-4 form-group">
            <label>Brand {!! (@$mendatory['brand'])?'':$starMark !!}</label>
            {!! dropdownList(6,@$post_data,@$mendatory['brand']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['sku']) && $searchAreaOption['sku'] == 1)
        <div class="col-lg-4 form-group">
            <label>SKU {!! (@$mendatory['sku'])?'':$starMark !!}</label>
            {!! dropdownList(7,@$post_data,@$mendatory['sku']) !!}
        </div>
    @endif
    @if(isset($searchAreaOption['month']) && $searchAreaOption['month'] == 1)
        <div class="col-lg-4 form-group">
            <label>Month {!! (@$mendatory['month'])?'':$starMark !!}</label>
            <input type="text" name="month[]" placeholder="Month" value="{{date('F-Y')}}" class="form-control user-error monthpicker {{(@$mendatory['month'])?'':'mendatory'}}" aria-invalid="true">
        </div>
    @endif
        @if(isset($searchAreaOption['daterange']) && $searchAreaOption['daterange'] == 1)
            <div class="col-lg-4 form-group">
                <label>Date Range {!! (@$mendatory['daterange'])?'':$starMark !!}</label>
                <input type="text" name="created_at[]" placeholder="ASO Name" value="" class="form-control user-error date_range_converted {{(@$mendatory['year'])?'':'mendatory'}}" aria-invalid="true">
            </div>
        @endif

        @if(isset($searchAreaOption['package']) && $searchAreaOption['package'] == 1)
            <div class="col-lg-4 form-group">
                <label>Package {!! (@$mendatory['package'])?'':$starMark !!}</label>
                {!! dropdownList(11,@$post_data,@$mendatory['package']) !!}
            </div>
        @endif


        @if(isset($searchAreaOption['year']) && $searchAreaOption['year'] == 1)
            <div class="col-lg-4 form-group">
                <label>Year {!! (@$mendatory['year'])?'':$starMark !!}</label>
                <input type="text" name="year[]" placeholder="Year" value="" class="form-control user-error yearpicker {{(@$mendatory['year'])?'':'mendatory'}}" aria-invalid="true">
                {{--<input class="date-own form-control" style="width: 300px;" type="text">--}}
            </div>
        @endif
    @if(isset($searchAreaOption['datepicker']) && $searchAreaOption['datepicker'] == 1)
        <div class="col-lg-4 form-group">
            <label>Date Picker {!! (@$mendatory['datepicker'])?'':$starMark !!}</label>
            <input type="text" name="date[]" placeholder="Date Picker" value="" class="form-control user-error date_picker_converted {{(@$mendatory['datepicker'])?'':'mendatory'}}" aria-invalid="true">
        </div>
    @endif

    @if(isset($searchAreaOption['dss_report_type']) && $searchAreaOption['dss_report_type'] == 1)
        <div class="col-lg-4 form-group">
            <label>DSS Report Type {!! (@$mendatory['dss_report_type'])?'':$starMark !!}</label>
            <select id="dss_report_type" class="form-control dss_report_type multiselect {{(@$mendatory['year'])?'':'mendatory'}}" name="dss_report_type[]">
                <option value="">None Selected</option>
                <option value="routes">Route</option>
                <option value="aso">ASO</option>
            </select>
        </div>
    @endif

    @if(isset($searchAreaOption['ranking_report']) && $searchAreaOption['ranking_report'] == 1)
        <div class="col-lg-4 form-group">
            <label>Ranking Report Type {!! (@$mendatory['ranking_report'])?'':$starMark !!}</label>
            <select id="dss_report_type" class="form-control dss_report_type multiselect {{(@$mendatory['year'])?'':'mendatory'}}" name="ranking_report[]">
                <option value="">None Selected</option>
                <option value="aso">ASO</option>
                <option value="house">House</option>
                <option value="territory">Territory</option>
                <option value="region">Region</option>
                <option value="zone">Zone</option>
            </select>
        </div>
    @endif

    @if(isset($searchAreaOption['view-report']) && $searchAreaOption['view-report'] == 1)
        <div class="col-lg-4 form-group">
            <label>View Report {!! (@$mendatory['view-report'])?'':$starMark !!}</label>
            <select id="view_report_type" class="form-control dss_report_type singlemultiselect {{(@$mendatory['year'])?'':'mendatory'}}"  name="view_report[]">

                @foreach(viewReportOptionList() as $key=>$value)
                    @if(Auth::user()->user_type == 'zone')
                        @if($value->value != 'zone')
                            <option {{($value->value == 'house')?'selected':''}} value="{{$value->value}}">{{$value->level}}</option>
                        @endif
                    @elseif((Auth::user()->user_type == 'region'))
                        @if(($value->value != 'zone') && ($value->value != 'region'))
                            <option {{($value->value == 'house')?'selected':''}} value="{{$value->value}}">{{$value->level}}</option>
                        @endif
                    @elseif(Auth::user()->user_type == 'territory')
                        @if(($value->value != 'zone') && ($value->value != 'region') && ($value->value != 'territory'))
                            <option {{($value->value == 'house')?'selected':''}} value="{{$value->value}}">{{$value->level}}</option>
                        @endif
                    @elseif(Auth::user()->user_type == 'house')
                        @if(($value->value != 'zone') && ($value->value != 'region') && ($value->value != 'territory') && ($value->value != 'house'))
                            <option {{($value->value == 'house')?'selected':''}} value="{{$value->value}}">{{$value->level}}</option>
                        @endif
                    @else
                        <option {{($value->value == 'house')?'selected':''}} value="{{$value->value}}">{{$value->level}}</option>
                    @endif
                @endforeach
            </select>
        </div>
    @endif


        @if(isset($searchAreaOption['Ordersalemode']) && $searchAreaOption['Ordersalemode'] == 1)
            <div class="col-lg-4 form-group">
                <label>Order Sale Mode {!! (@$mendatory['Ordersalemode'])?'':$starMark !!}</label>
                <select id="Ordersalemode" class="form-control Ordersalemode singlemultiselect {{(@$mendatory['Ordersalemode'])?'':'mendatory'}}" name="Ordersalemode[]">
                    <option value="">None Selected</option>
                    <option value="Primary">Primary</option>
                    <option value="Secondary">Secondary</option>
                </select>
            </div>
        @endif





</div>

<style>
    .datepicker{
        z-index: 9999 !important;
    }
</style>


<script>

$(function () {

    var privilegeObj,compare;

    function onChangeZones(selectedOptions){
//       console.log(selectedOptions);
        var region = setRegion(selectedOptions,'zones_id');
//        console.log(region);
        var territory = setTerritory(region,'regions_id');
        var house = setHouse(territory,'territories_id');
        var house_single = setHouseSingle(territory,'territories_id');
        var aso = setAso(house,'distribution_houses_id');
        var route = setRoute(aso,'so_aso_user_id');
    }
    function onChangeRegion(selectedOptions){
        var territory = setTerritory(selectedOptions,'regions_id');
        var house = setHouse(territory,'territories_id');
        var house_single = setHouseSingle(territory,'territories_id');
        var aso = setAso(house,'distribution_houses_id');
        var route = setRoute(aso,'so_aso_user_id');
    }
    function onChangeTerritory(selectedOptions){
        var house = setHouse(selectedOptions,'territories_id');
        var house_single = setHouseSingle(selectedOptions,'territories_id');
        var aso = setAso(house,'distribution_houses_id');
        var route = setRoute(aso,'so_aso_user_id');
    }
    function onChangeHouse(selectedOptions){
        var aso = setAso(selectedOptions,'distribution_houses_id');
        var route = setRoute(aso,'so_aso_user_id');
    }
    function onChangeAso(selectedOptions){
        var route = setRoute(selectedOptions,'so_aso_user_id');
    }



    $.ajax({
        data:{'_token':'{{csrf_token()}}'},
        type:"POST",
        url: '{{URL::to('get-allPlaces')}}',
        success: function (data) {
            privilegeObj = $.parseJSON(data);
//            console.log(privilegeObj);

            $('#zones_id').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                onChange: function() {
                    var selectedOptions = [];
                    $('#zones_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    onChangeZones(selectedOptions);
                    //setCompany(pt,'id');
                },
                onSelectAll: function() {
                    var selectedOptions =[];
                    $('#zones_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeZones(selectedOptions);
                },
                onDeselectAll:function(){
                    var selectedOptions =[];
                    $('#zones_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeZones(selectedOptions);
                }

            }).multiselect('selectAll', false)
                .multiselect('updateButtonText');

            $('#regions_id').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                onChange: function() {
                    var selectedOptions = [];
                    $('#regions_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //alert(JSON.stringify(selectedOptions));
                    onChangeRegion(selectedOptions);
                    //setCompany(pt,'id');
                },
                onSelectAll: function() {
                    var selectedOptions =[];
                    $('#regions_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeRegion(selectedOptions);
                },
                onDeselectAll:function(){
                    var selectedOptions =[];
                    $('#regions_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeRegion(selectedOptions);
                }
            }).multiselect('selectAll', false)
                .multiselect('updateButtonText');

            $('#territories_id').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                onChange: function() {
                    var selectedOptions = [];
                    $('#territories_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //alert(JSON.stringify(selectedOptions));
                    onChangeTerritory(selectedOptions);
                    //setCompany(pt,'id');
                },
                onSelectAll: function() {
                    var selectedOptions =[];
                    $('#territories_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeTerritory(selectedOptions);
                },
                onDeselectAll:function(){
                    var selectedOptions =[];
                    $('#territories_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeTerritory(selectedOptions);
                }
            }).multiselect('selectAll', false)
                .multiselect('updateButtonText');

            $('#house_id').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                onChange: function() {
                    var selectedOptions = [];
                    $('#house_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //alert(JSON.stringify(selectedOptions));
                    onChangeHouse(selectedOptions);
                    //setCompany(pt,'id');
                },
                onSelectAll: function() {
                    var selectedOptions =[];
                    $('#house_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeHouse(selectedOptions);
                },
                onDeselectAll:function(){
                    var selectedOptions =[];
                    $('#house_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeHouse(selectedOptions);
                }
            }).multiselect('selectAll', false)
                .multiselect('updateButtonText');

            $('#house_id_single').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true
            });

            $('#aso_id').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                onChange: function() {
                    var selectedOptions = [];
                    $('#aso_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //alert(JSON.stringify(selectedOptions));
                    onChangeAso(selectedOptions);
                    //setCompany(pt,'id');
                },
                onSelectAll: function() {
                    var selectedOptions =[];
                    $('#aso_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeAso(selectedOptions);
                },
                onDeselectAll:function(){
                    var selectedOptions =[];
                    $('#aso_id option:selected').map(function(a, item){return selectedOptions.push(item.value);});
                    //console.log(selectedOptions);
                    onChangeAso(selectedOptions);
                }
            }).multiselect('selectAll', false)
                .multiselect('updateButtonText');

            $('#routes_id').multiselect({
                buttonWidth: '100%',
                includeSelectAllOption: true,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true
            }).multiselect('selectAll', false)
                .multiselect('updateButtonText');
        }
    });


    function setRegion(sel_val, comp){
//        console.log(sel_val);
//        console.log(privilegeObj.regions);
//       var jak = sel_val.split(',');
        var arr = [];
        var sel = ['multiselect-all'];
        privilegeObj.regions.forEach(function(e){
            if($.inArray(e[comp], sel_val)){
                sel_val.forEach(function (en) {
                    if(e[comp] == en){
                        var group = {label: e.name, value: e.id};
                        arr.push(group);
                        sel.push(e.id);
                    }
                });
            }
        });
        $('#regions_id').multiselect('dataprovider', arr);
        $('#regions_id').multiselect('select', sel);
        $('#regions_id').multiselect('rebuild');
        return sel;
    }

    function setTerritory(sel_val,comp){
        var arr = [];
        var sel = ['multiselect-all'];
        privilegeObj.territories.forEach(function(e){
            if($.inArray(e[comp], sel_val)){
                sel_val.forEach(function (en) {
                    if(e[comp] == en){
                        var group = {label: e.name, value: e.id};
                        arr.push(group);
                        sel.push(e.id);
                    }
                });
            }
        });
        $('#territories_id').multiselect('dataprovider', arr);
        $('#territories_id').multiselect('select', sel);
        return sel;
    }


    function setHouse(sel_val,comp){
        var arr = [];
        var sel = ['multiselect-all'];
        privilegeObj.houses.forEach(function(e){
            if($.inArray(e[comp], sel_val)){
                sel_val.forEach(function (en) {
                    if(e[comp] == en){
                        var group = {label: e.name, value: e.id};
                        arr.push(group);
                        sel.push(e.id);
                    }
                });
            }
        });
        $('#house_id').multiselect('dataprovider', arr);
        $('#house_id').multiselect('select', sel);
        return sel;
    }

    function setHouseSingle(sel_val,comp){
        var arr = [];
        var sel = ['multiselect-all'];
        privilegeObj.houses.forEach(function(e){
            if($.inArray(e[comp], sel_val)){
                sel_val.forEach(function (en) {
                    if(e[comp] == en){
                        var group = {label: e.name, value: e.id};
                        arr.push(group);
                        sel.push(e.id);
                    }
                });
            }
        });
        $('#house_id_single').multiselect('dataprovider', arr);
        //$('#house_id_single').multiselect('select', sel);
        return sel;
    }


    function setAso(sel_val,comp){
        var arr = [];
        var sel = ['multiselect-all'];
        privilegeObj.aso.forEach(function(e){
            if($.inArray(e[comp], sel_val)){
                sel_val.forEach(function (en) {
                    if(e[comp] == en){
                        var group = {label: e.name, value: e.id};
                        arr.push(group);
                        sel.push(e.id);
                    }
                });
            }
        });
        $('#aso_id').multiselect('dataprovider', arr);
        $('#aso_id').multiselect('select', sel);
        return sel;
    }

    function setRoute(sel_val,comp){
        var arr = [];
        var sel = ['multiselect-all'];
        privilegeObj.route.forEach(function(e){
            if($.inArray(e[comp], sel_val)){
                sel_val.forEach(function (en) {
                    if(e[comp] == en){
                        var group = {label: e.name, value: e.id};
                        arr.push(group);
                        sel.push(e.id);
                    }
                });
            }
        });
        $('#routes_id').multiselect('dataprovider', arr);
        $('#routes_id').multiselect('select', sel);
        return sel;
    }

});


</script>