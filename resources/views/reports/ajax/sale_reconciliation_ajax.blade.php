@if(isset($monthly_sale_reconciliation) && count($monthly_sale_reconciliation) > 0)
    @foreach($monthly_sale_reconciliation as $house_key=>$house_value)
        <h4 align="center"><b>{{$house_key}}</b></h4>
        <table class="table-bordered  table" BORDERCOLOR=RED>
            <thead>
            <th>Category</th>
            <th>Brand</th>
            <th>SKU</th>
            <th>Openning Stock</th>
            <th>Lifting</th>
            <th>Sales</th>
            <th>Closing</th>
            </thead>
            <tbody>
            @if(isset($memo_structure))
                @foreach($memo_structure as $category_key=>$category_value)
                    <tr style="border-color:#767b84;">
                        <td rowspan="{{ array_sum(array_map("count", $category_value))}}"
                            style="border-color:#767b84;">{{$category_key}}</td>
                        @foreach($category_value as $brand_key=>$brand_value)
                            <?php
                                //debug(array_sum($brand_value),1);
                            ?>
{{--                            <td rowspan="{{ array_sum(array_map("count", $brand_value))}}"--}}
                            <td rowspan="{{ count($brand_value)}}"
                                style="border-color:#767b84;">{{$brand_key}}</td>
                            @foreach($brand_value as $key=>$value)
                                <td style="border-color:#767b84;">{{$key}}</td>
                                @if(isset($house_value[$value]))
                                    <td style="border-color:#767b84;">{{$house_value[$value]['openning']}}</td>
                                    <td style="border-color:#767b84;">{{$house_value[$value]['lifting']}}</td>
                                    <td style="border-color:#767b84;">{{$house_value[$value]['sale']}}</td>
                                    <td style="border-color:#767b84;">{{$house_value[$value]['closing']}}</td>
                                @else
                                    <td style="border-color:#767b84;">0</td>
                                    <td style="border-color:#767b84;">0</td>
                                    <td style="border-color:#767b84;">0</td>
                                    <td style="border-color:#767b84;">0</td>
                                @endif
                    </tr>
                @endforeach
                @endforeach
                @endforeach
            @endif
            </tbody>
        </table>
    @endforeach
@endif