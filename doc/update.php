<?php
require_once('../config/config.php');
session_start();
//error_reporting(0);

$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

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

if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}

$title = $_POST['title'];
$desc = $_POST['desc'];
$city = $_POST['city'];
$content = $_POST['content'];
$country = $_POST['country'];
$path = $_POST['path'];



$sql2="select geocountries_regions.*,geocities.* from geocities left join geocountries_regions on geocountries_regions.ISO = geocities.Country_RegionCodeISO where geocities.AsciiName = '$city' and  geocountries_regions.Country_RegionName = '$country'";
$res = mysqli_query($connection, $sql2);
$rows = mysqli_fetch_assoc($res);
if(mysqli_num_rows($res) > 0) {
    $cityID = $rows['GeoNameID'];
    $countryISO = $rows['Country_RegionCodeISO'];
}else{
    header("Location:upload.php?id=$imageid");
}


if(isset($_GET['id'])) {
    $imageid = $_GET['id'];
    $sql3 = "select * from travelimage where ImageID =$imageid";
    $result = mysqli_query($connection, $sql3);
    $row = mysqli_fetch_assoc($result);
    $username = $_SESSION['UserName'];


    mysqli_query($connection, "UPDATE travelimage SET Title='$title',Description='$desc',Content='$content',CityCode='$cityID',Country_RegionCodeISO='$countryISO' WHERE ImageID='$imageid'") or die('修改数据出错：' . mysqli_error());

    header("Location:myPhoto.php");
}else{
  //  header("Location:insert.php");
}
?>
