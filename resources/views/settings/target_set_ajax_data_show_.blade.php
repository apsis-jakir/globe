<table id="sort" class="table table-bordered">
    <thead>
        <tr>
            <th>{{$type}}</th>
            @foreach($brands as $brand)
                <th style="text-align:center" colspan="{{$brand->total}}">{{$brand->brand_name}}</th>
            @endforeach
        </tr>
        <tr>
            <th>sku</th>
            @foreach($skues as $skue)
                <td style="text-align: center">
                    <?php echo str_replace(' ',"&nbsp;",$skue->sku_name);?>
                    <input type="text" placeholder="Target">
                </td>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach($geographies as $geography)
        <tr>
            <th><?php echo str_replace(' ','&nbsp;',$geography->gname); ?></th>
            @foreach($skues as $skue)
                <td style="text-align: center">
                    <input style="width: 50px;" type="text" placeholder="base">
                    <input style="width: 50px;" type="text" placeholder="target">
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

