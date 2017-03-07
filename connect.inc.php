<?php
$mysql_host='sql2.njit.edu';
$mysql_user='kn259';
$mysql_pass='7bI1DDsD';
if(!mysql_connect($mysql_host,$mysql_user,$mysql_pass)||!mysql_select_db($mysql_user)){
   die(mysql_error());
}
?>