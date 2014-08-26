<?php
/**
 * Short description for register.php
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
include "head.php";
?>

<div class="col-xs-6 col-md-4 mylogin" >
<form class="form-horizontal" role="form" method="post">
  <div class="form-group">
    <label for="name" class="col-sm-4 control-label">用户名</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="name" name="name" placeholder="用户名">
    </div>
  </div>

  <div class="form-group">
    <label for="pass" class="col-sm-4 control-label">密码</label>
    <div class="col-sm-8">
      <input type="password" class="form-control" id="pass" name="pass" placeholder="密码">
    </div>
  </div>

  <div class="form-group">
    <label for="confirm_pass" class="col-sm-4 control-label">确认密码</label>
    <div class="col-sm-8">
      <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" placeholder="再次输入密码">
    </div>
  </div>

    <!-- 注册场上位置 -->
  <div class="form-group">
    <label for="team_position" class="col-sm-4 control-label">第一位置</label>
    <div>
        <div id="team_position">
        <label class="radio-inline">
          <input type="radio" name="pri_pos" value="f"> 前锋
        </label>
        <label class="radio-inline">
          <input type="radio" name="pri_pos" value="m"> 中场
        </label>
        <label class="radio-inline">
          <input type="radio" name="pri_pos" value="b"> 后卫
        </label>
        </div>
    </div>
  </div>

  <div class="form-group">
    <label for="team_position2" class="col-sm-4 control-label">第二位置</label>
    <div>
        <div id="team_position2">
        <label class="radio-inline">
          <input type="radio" name="sec_pos" value="f"> 前锋
        </label>
        <label class="radio-inline">
          <input type="radio" name="sec_pos" value="m"> 中场
        </label>
        <label class="radio-inline">
          <input type="radio" name="sec_pos" value="b"> 后卫
        </label>
        </div>
    </div>
  </div>

  <div class="form-group" align="center">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" name="submit" value="submit">注册</button>
    </div>
  </div>
</form>
</div>
<?php include_once "footer.php"; ?>
