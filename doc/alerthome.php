<?php
error_reporting(0);
session_start();
require_once('../config/config.php');
/*
function ImageID(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
    $sql = "select distinct ImageID from travelimagefavor limit 0,6";
    $result = mysqli_query($connection, $sql);
    while ($rows = mysqli_fetch_assoc($result)) {
        $res = $rows['ImageID'];
        $sql2 = "select * from travelimage  where ImageID = '$res'";
        $results = mysqli_query($connection,$sql2);
        while ($row = mysqli_fetch_assoc($results)) {
            outputSinglePhoto($row);
        }
    }
}
*/
function outputSinglePhoto($row){
    $id=$row['ImageID'];
    echo '<li class="img1">';
    echo '<a href="detail.php?id='.$id.'">';
    echo  '<img  src="../img/travel-images/medium/' . $row['PATH'] .'" class="cover">';
    echo '</a>';
    echo '<div class="caption">';
    echo '<h3>';
    echo constructPhotoLink($row['ImageID'], $row['Title']);
    echo '</h3>';
    echo '<p><span id="desc">';
    echo $row['Description'];
    echo '</span></p>';
    echo '</div>';  // end class=content
    echo '</li>';
}

function changeImg(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }

    $sql = "SELECT * FROM travelimage  ORDER BY  RAND() limit 0,6";
    $result = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        outputSinglePhoto($row);
    }

}
function constructPhotoLink($id, $label) {

    $link = '<a href="detail.php?id='.$id.'">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}
function constructLink() {
    $name=$_GET['name'];
    echo '<a href="alerthome.php?name=' . $name . '">';
}

function Checklogin(){
    if(isset($_SESSION['UserName'])){
        $name = $_SESSION['UserName'];}
    if($name == null){
        echo '<h3><a href="userlogin.php">Log In</a></h3>';
    }
    else{
        echo' <h3><a href="#">My Account</a></h3>';
        echo' <ul class="dropdown-menu">';
        echo' <li><i class="iconfont icon-shangchuan"></i><a href="./upload.php">Upload</a></li>';
        echo' <li><i class="iconfont icon-picture"></i><a href="./myPhoto.php">My Photo</a></li>';
        echo' <li><i class="iconfont icon-shoucang"></i><a href="./myfavorite.php">My Favorite</a></li>';
        echo' <li><i class="iconfont icon-denglu"></i><a href="./logout.php">Log Out</a></li>';
        echo' </ul>';
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/reset.css" rel="stylesheet">
    <link href="../css/iconfont/iconfont.css" rel="stylesheet">
    <link href="../css/home.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../js/home.js"></script>
    <script src="../js/dropdown.js"></script>

    <title>home</title>
</head>
<body>
<header>
    <nav>
        <h3>
            <div class="div-left">
                <a href="../index.php">
                    <img src="../img/logo1.png" width="50" alt="logo">
                </a>
                <a href="./alerthome.php" id="current">Home</a>
                <a href="./browse.php">Browser</a>
                <a href="./search.php">Search</a>
            </div>
        </h3>
        <div class="wrap">
            <?php Checklogin() ;?>
        </div>
    </nav>
</header>
<div class="content">
    <div class="picture1">
        <img src="../img/00002.jpg" width="100%" alt="website banner">
    </div>
    <ul>
        <?php changeImg();?>
    </ul>
</div>
<div class="box">
    <div class="box1">
        <button type="button" name="arrow">
            <img src="../img/arrow.png" width="35px">
        </button><br>
    </div>
    <button type="button" id="demo" >
        <a href="alerthome.php"><img src="../img/repeat.png" width="35px"></a>
    </button>
</div>
<footer>
    <div class="row1">
        <div class="footer-left">
            <p><span class="text1">关于我们</span></p><br>
            <p><a href="#">联系我们</a></p><br>
            <p><a href="#">加入我们</a></p>
        </div>
        <div class="footer-m">
            <p><span class="text1">关注我们</span></p><br>
            <p><a href="#">新浪微博</a></p><br>
            <p><a href="#">微信公众号</a></p>
        </div>
        <div class="footer-right">
            <p><span class="text1">扫描二维码</span></p>
            <p><span class="text1">下载手机客户端</span></p>
            <img src="../img/wechat.jpg" width="80px"/>
        </div>
    </div>
    <div class="copyright">
        <img src="../img/logo2.png" width="70px">
        <span class="copyrighttxt">Copyright © 2020 Alyssa. All Rights Reserved. 备案号：沪ICP备18307110513号</span>
    </div>
</footer>
</body>
</html>