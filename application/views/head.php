<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title;?></title>
    <link rel="stylesheet" herf="/bootstrap/css/mystyle.css">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap-datetimepicker.min.css">
    <script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">北苑伙伴</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="/ci/index/showActivity">活动详情</a></li>
            <li><a href="/ci/index/createActivity">创建活动</a></li>
            <li><a href="/ci/index/personal">个人资料</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          <?php 
                if (isset($_SESSION['AUTH_USER'])) {
                    echo "<li><a href='#'>欢迎您, {$_SESSION['AUTH_USER']}</a></li>";
                    echo "<li><a href='logout'>注销</a></li>";
                } else {
                    echo "<li><a href='index'>您尚未登录，点击这里进行登录</a></li>";
                }
          ?>
          </ul>
        </div><!-- /.navbar-collapse -->

      </div><!-- /.container-fluid -->
    </nav>
