<div class="col-lg-12">
    <div class="col-lg-4 form-group">
        <label>ASO Name</label>
        <select class="form-control" name="requester_name">
            <option value="">Select ASO</option>
            <?php
            foreach($aso_name as $an)
            { ?>
            <option value="<?php echo $an->requester_name; ?>"><?php echo $an->requester_name; ?></option>
            <?php }
            ?>
        </select>


    </div>
    <div class="col-lg-4 form-group">
        <label>Distributor House</label>
        <select class="form-control" name="dh_name">
            <option value="">Select House</option>
            <?php
            foreach($houses as $house)
            { ?>
            <option value="<?php echo $house->dh_name; ?>"><?php echo $house->dh_name; ?></option>
            <?php }
            ?>
        </select>
    </div>
    <div class="col-lg-4 form-group">
        <label>Route</label>
        <select class="form-control" name="route_name">
            <option value="">Select Route</option>
            <?php
            foreach($routes as $route)
            { ?>
            <option value="<?php echo $route->route_name; ?>"><?php echo $route->route_name; ?></option>
            <?php }
            ?>
        </select>
    </div>
    <div class="col-lg-4 form-group">
        <label>Date Range</label>
        <input type="text" name="created_at" placeholder="ASO Name" value="" class="form-control user-error date_range" aria-invalid="true">
    </div>
</div>