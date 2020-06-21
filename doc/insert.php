<?php
require_once('../config/config.php');
session_start();
//error_reporting(0);

$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}

$title = $_POST['title'];
$desc = $_POST['desc'];
$city = $_POST['city'];
$content = $_POST['content'];
$country = $_POST['country'];
$path = $_POST['path'];
function findID($name){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }

    $sql = "SELECT UID FROM traveluser where UserName = '$name'";
    $result = mysqli_query($connection, $sql);
    $rows = mysqli_fetch_assoc($result);
    $uid = $rows['UID'];

    return $uid;
}

$userid= findID($_SESSION['UserName']);



$sql2="select geocountries_regions.*,geocities.* from geocities left join geocountries_regions on geocountries_regions.ISO = geocities.Country_RegionCodeISO where geocities.AsciiName = '$city' and  geocountries_regions.Country_RegionName = '$country'";
$res = mysqli_query($connection, $sql2);
$rows = mysqli_fetch_assoc($res);
$cityID = $rows['GeoNameID'];
$countryISO = $rows['Country_RegionCodeISO'];

$sql3="select ImageID from travelimage order by ImageID desc limit 0,1";
$result=mysqli_query($connection, $sql3);
$row = mysqli_fetch_assoc($result);
    $num =$row['ImageID'];

$imageid = $num+1;



mysqli_query($connection,"INSERT INTO travelimage(ImageID,PATH,Title,Description,Content,CityCode,Country_RegionCodeISO,UID) VALUES ('$imageid','$path','$title','$desc','$content','$cityID','$countryISO','$userid')") or die('添加数据出错：'.mysqli_error($connection));


header("Location:myPhoto.php");

?>
