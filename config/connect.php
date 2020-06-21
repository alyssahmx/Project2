<?php
 $server="localhost";//主机
 $db_username="testuser";//数据库用户名
 $db_password="mypassword";//数据库密码
 $con = mysqli_connect($server,$db_username,$db_password);//链接数据库
 if(!$con){
    die("数据库连接失败：".mysqli_error());
 }
 mysqli_select_db('test',$con);//选择数据库
?>