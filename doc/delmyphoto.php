
<?php
require_once('../config/config.php');
session_start();
//error_reporting(0);

$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}

function findID($name){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }

    $sql = "SELECT UID FROM traveluser where UserName = '$name'";
    $result = mysqli_query($connection, $sql);
    while($rows = mysqli_fetch_assoc($result)) {
        $uid = $rows['UID'];
    }
    return $uid;
}

$userid= findID($_SESSION['UserName']);


$id = $_GET['id'];



mysqli_query($connection,"DELETE FROM travelimage WHERE UID='$userid' and ImageID='$id'") or die('删除数据出错：'.mysqli_error());


mysqli_query($connection,"DELETE FROM travelimagefavor WHERE ImageID='$id'") or die('删除数据出错：'.mysqli_error());

header("Location:myPhoto.php");

?>