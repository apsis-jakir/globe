@extends('layouts.app')

@section('content')
    <div class="content-wrapper">

        <section class="content-header">
            <h1>
                {{$header_level}}
            </h1>
            {!! $breadcrumb !!}
        </section>



        <div class="row">
            <div class="col-xs-12">
                <div class="box">

                    <div class="box-body showSearchDataUnique">
                        <div style="text-align: center; border-bottom: 1px solid #ccc;">
                            @if($type == 'house')
                                <h2>{{$houseInfo->point_name}}-{{$houseInfo->market_name}}</h2>
                            @else
                                <h2>{{$routeInfo->point_name}}-{{$routeInfo->market_name}}</h2>
                                <h3>{{$routeInfo->routes_name}}-{{$routeInfo->routes_code}}</h3>
                            @endif

                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Target</th>
                                    <th>Sales</th>
                                    <th>Achievement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($memu_structure))
                                    @foreach($memu_structure as $brand=>$brand_value)
                                        <?php $sku = 0; ?>
                                        @foreach($brand_value as $sku_key=>$sku_value)
                                            <tr>
                                                <td>{{$sku_value}} - {{$sku_key}}</td>
                                                <td>{{$route_wise_performance[0]['result'][$sku_key][0]}}</td>
                                                <td>{{$route_wise_performance[0]['result'][$sku_key][1]}}</td>
                                                <td>{{$route_wise_performance[0]['result'][$sku_key][2]}}</td>
                                            </tr>

                                        @endforeach
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>


                </div>



            </div>
        </div>


@endsection