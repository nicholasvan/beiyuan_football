<?php
/**
 * Short description for activty.php
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
include "head.php";
$default['date']   = 'Sat'; 
$default['limit']  = 21;
$default['pos']    = '奥体森林公园北园3号场';
$default['time']   = '20:00 - 22:00';

$default_date = date('m-d', strtotime($default['date']));
?>

<div class="col-md-8" >
    <label>活动总览</label>
    <table class="table table-bordered"> 
        <tr>
            <th>报名<th>活动日期</th><th>报名人数/限制人数</th><th>活动时段</th><th>活动地点</th><th>参加人员</th><th>分组/查看</th>
        <tr>
        <?php 
            foreach ($acts->result() as $one_row) {
                $date = $one_row->date;
                $limit = $one_row->limit;
                $pos  = $one_row->pos;
                $time = $one_row->time;
                $members = json_decode($one_row->members, true);
                $user_already_join = false;
                if (!empty($members)) {
                    $p_join = "";
                    $join_player = array_keys($members);
                    if (in_array($_SESSION['AUTH_USER'], $join_player)) {
                        $user_already_join = true;
                    }
                    $exceed = count($join_player) > $limit;
                    $limit = count($join_player) . "/" . $limit;
                    foreach ($join_player as $player) {
                        $p_join .= "$player;";
                    }
                }else{
                    $limit = 0 . "/" . $limit;
                    $p_join = "-";
                }
                if ($exceed) {
                    if ($user_already_join) {
                        echo "<tr><td><button class='join' id='$date' name='quit'>取消</button></td><td>$date</td><td style='color:red'>$limit</td>
                            <td>$time</td><td>$pos</td><td class='members' date='$date'>$p_join</td>
                            <td><button class='grouping' date='$date'>分组</button><button>查看</button></td></tr>"; 
                    } else {
                        echo "<tr><td><button class='join' id='$date' name='join'>参加</button></td><td>$date</td><td style='color:red'>$limit</td>
                            <td>$time</td><td>$pos</td><td class='members' date='$date'>$p_join</td>
                            <td><button class='grouping' date='$date'>分组</button><button>查看</button></td></tr>"; 
                    }
                } else {
                    if ($user_already_join) {
                        echo "<tr><td><button class='join' id='$date' name='quit'>取消</button></td><td>$date</td><td>$limit</td>
                            <td>$time</td><td>$pos</td><td class='members' date='$date'>$p_join</td>
                            <td><button class='grouping' date='$date'>分组</button><button>查看</button></td></tr>"; 
                    } else {
                        echo "<tr><td><button class='join' id='$date' name='join'>参加</button></td><td>$date</td><td>$limit</td>
                            <td>$time</td><td>$pos</td><td class='members' date='$date'>$p_join</td>
                            <td><button class='grouping' date='$date'>分组</button><button>查看</button></td></tr>"; 
                    }
                }
            }
        ?> 
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">参加人员</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
        <button type="button" id='confirm' class="btn btn-primary">确定</button>
      </div>
    </div>
  </div>
</div>

<script>
$('.join').click(function(){
    if (this.name == 'join') {
        var join = confirm('确认报名参加'+this.id+'的活动?');
    } else {
        var join = confirm('确认退出'+this.id+'的活动?');
    }
    if (join) {
        $.post('join', {'date':this.id, 'type':this.name}, function(ret){
            if (ret == 'ok') {
                alert('操作成功');
            } else {
                alert('操作失败');
            }
            location.reload();
        })
    }
});

$('.members').dblclick(function(){
    $('#myModal').modal();
    var date = $(this).attr('date');
    $('#myModalLabel').html(date + '参加队员');
    $('.modal-body').html("<textarea date='" + date + "' rows='5' style='width:100%'>"+ $(this).html()+"</textarea>");
});

$('#confirm').click(function(e){
    var textarea = $('.modal-body textarea');
    var date     = textarea.attr('date');
    var players  = textarea.val();
    $.post('editJoinList', {'date':date, 'players':players}, function(ret){
        if (ret == 'ok') {
            alert('操作成功');
        } else {
            alert('操作失败');
        }
        location.reload();
    })
});

$('.grouping').click(function(){
    $.post('grouping', {'date':date, 'players':players}, function(ret){
        if (ret == 'ok') {
            alert('分组完成，可点击查看');
        } else {
            alert('分组失败');
        }
    })
});
</script>
<?php 
include "footer.php"; 
?>
