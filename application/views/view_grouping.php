<?php
/**
 * Short description for view_grouping.php
 * @author wangkun <wangkun@wangkundeMacBook-Pro.local>
 */
include "head.php";
echo "<table class='table table-bordered'>";
echo "<th>组号</th><th>组员</th>";
foreach ($group as $gnum => $players) {
    $out = "";
    echo "<tr><td>" . ($gnum + 1) . "</td><td>";
    foreach ($players as $pname) {
        $out .= $pname."; ";
    }
    echo $out;
    echo "</td></tr>";
}
echo "</table>";
?>

<?
include "footer.php";
