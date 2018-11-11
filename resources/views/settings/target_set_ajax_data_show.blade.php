<div style="overflow: scroll;">
    <table id="d_table" class="table table-bordered">
        <thead>
        <tr>
            <th>{{strtoupper($type)}}</th>
            @foreach($brands as $brand)
                <th style="text-align:center" colspan="{{$brand->total}}">{{$brand->brand_name}}</th>
            @endforeach
        </tr>
        <tr>
            <th>SKU</th>
            @foreach($skues as $skue)
                <?php

                echo "
                    <script>
                        var dClass = '".$skue->brands_id.'_'.$skue->id."';
                        $(document).on('input','.target_'+dClass,function(){
                            var targetValue = $(this).val();
                            var baseValue = parseInt($(this).parent().parent().find('.base_total').val());
                            var uniqueRef = $(this).attr('unique_ref');

                            $('.base_distribute_'+uniqueRef).each(function(){
                                var dv = parseInt($(this).val());
                                var basePercent = ((dv*100)/baseValue)
                                var targetDistribute = ((targetValue*basePercent)/100)
                                $(this).parent().find('.target_distribute_'+uniqueRef).val(Math.round(targetDistribute));
                            });
                        });
                    </script>
                ";
                if($targetType == 'edit')
                {
                    $existing = targetHelper::totalJsonValue($type,$skue->short_name,$target_month,$existingValue);
                }
                else
                {
                    $existingTarget = targetHelper::totalExistingJsonValue($type,$skue->short_name,$target_month);
//                debug($existingTarget,1);
                }

                //            debug($base,1);

                ?>
                <td style="text-align: center">
                    <?php echo str_replace(' ',"&nbsp;",$skue->sku_name);?>
                    <div style="width: 150px; overflow: hidden;">
                        <div style="float: left;">
                            <input
                                    class="base_total {{'base_'.$skue->brands_id.'_'.$skue->id}}"
                                    style="width: 75px;"
                                    type="text"
                                    placeholder="Base"
                                    value="<?php echo (($targetType == 'edit')?$existing['base']:array_sum($base[$skue->id]));?>">
                        </div>
                        <div style="float: left;">
                            <input
                                    unique_ref="{{$skue->brands_id.'_'.$skue->id}}"
                                    class="{{'target_'.$skue->brands_id.'_'.$skue->id}}"
                                    style="width: 75px;"
                                    type="text"
                                    placeholder="Target"
                                    value="<?php echo (($targetType == 'edit')?$existing['target']:$existingTarget['target']);?>">
                        </div>
                    </div>
                    {{--<input type="text" placeholder="Target">--}}
                </td>
            @endforeach
        </tr>
        </thead>
        <tbody>

        @foreach($geographies as $geography)
            <tr>
                <th>
                    <input type="hidden" name="geography_id[]" value="{{$geography->id}}">
                    <?php echo str_replace(' ','&nbsp;',$geography->gname); ?>
                </th>

                @foreach($skues as $skue)
                    <?php
                    $existing = targetHelper::getTargetJsonValue($type,$geography->id,$target_month,$existingValue);
                    $existingTarget = targetHelper::totalExistingJsonValue($type,$skue->short_name,$target_month);
                    $targetValue = 0;
                    if($existing)
                    {
                        $targetValue = $existing['target'][$skue->short_name];
                    }
                    else
                    {
                        if(array_sum($base[$skue->id]) > 0)
                        {
                            $targetValue = round((((($baseData[$geography->id][$skue->brands_id][$skue->id]*100)/array_sum($base[$skue->id]))*$existingTarget['target'])/100));
                        }
                        else
                        {
                            $targetValue = 0;
                        }
                    }
                    ?>
                    <?php //debug($base,1); ?>
                    <td style="text-align: center">
                        <input
                                name="base_distribute[<?php echo $geography->id; ?>][<?php echo $skue->brands_id; ?>][<?php echo $skue->short_name; ?>]"
                                class="{{'base_distribute_'.$skue->brands_id.'_'.$skue->id}}"
                                readonly style="width: 50px;"
                                type="number"
                                {{--value="{{$baseData[$geography->id][$skue->brands_id][$skue->id]}}"--}}
                                value="<?php echo (($existing)?$existing['base'][$skue->short_name]:$baseData[$geography->id][$skue->brands_id][$skue->id]); ?>">
                        <input
                                name="target_distribute[<?php echo $geography->id; ?>][<?php echo $skue->brands_id; ?>][<?php echo $skue->short_name; ?>]"
                                class="{{'target_distribute_'.$skue->brands_id.'_'.$skue->id}}"
                                style="width: 50px;"
                                type="text"
                                {{--value="0">--}}
                                value="<?php echo $targetValue; ?>">
                    </td>
                @endforeach
            </tr>

        @endforeach
        </tbody>
    </table>
</div>

<div style="text-align: right">
    <input type="submit" value="Confirm" class="btn btn-primary">
</div>


