<?php
/**
 * Short description for Create_activity.php
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
include "head.php";
$default['date']   = 'Sat'; 
$default['limit']  = 21;
$default['pos']    = '奥体森林公园北园3号场';
$default['time']   = '20:00';
?>
<div class="col-md-8" style="margin-left:20px;margin-top:20px" >
    <label>创建活动</label>
    <form class="form-horizontal" role="form" method="post">

      <div class="form-group">
        <label for="date" class="col-sm-2 control-label">日期</label>
        <div class="col-sm-10">
          <input type="text" class="form-control date" id="date" name="date" placeholder="活动时间" 
                value="<?php echo date('m-d', strtotime($default['date']));?>"> 
        </div>
      </div>

      <div class="form-group">
        <label for="limit" class="col-sm-2 control-label">人数限制</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="limit" name="limit" placeholder="人数限制" value="<?php echo $default['limit'];?>">
        </div>
      </div>

      <div class="form-group">
        <label for="time" class="col-sm-2 control-label">活动时间</label>
        <div class="col-sm-10">
            <input type="text" class="form-control time" id="time" name="time" placeholder="活动时间" value="<?php echo $default['time'];?>">
        </div>
      </div>

      <div class="form-group">
        <label for="pos" class="col-sm-2 control-label">活动地点</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="pos" name="pos" placeholder="活动地点" value="<?php echo $default['pos'];?>">
        </div>
      </div>

      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default" name="submit">创建</button>
        </div>
      </div>
    </form>
</div>

<?php include "footer.php"; ?>
