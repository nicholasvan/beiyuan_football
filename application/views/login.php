<?php
/**
 * Short description for login.php
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
include_once "head.php";
if (isset($invalid)) {
    echo '<div class="alert alert-danger" role="alert">用户名/密码错误</div>';
}
?>
<div class="col-xs-6 col-md-4 mylogin" >
<form class="form-horizontal" role="form" method="post" action="login">
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
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="remember"> 记住登陆
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default" name="submit">登陆</button>
      <a href='register' class="btn btn-primary">注册</a>
    </div>
  </div>
</form>
</div>

<?php
include_once "footer.php";
