<?php
//error_reporting(0);
session_start();
require_once('./config/config.php');


function ImageID(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
    $sql = "select t.ImageID from (select ImageID,count(*) counts from travelimagefavor group by ImageID) t order by counts desc limit 6";

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

function outputSinglePhoto($row){
    echo '<li class="img1">';
    $img = '<img  src="../img/travel-images/medium/' . $row['PATH'] .'" class="cover">';
    echo constructPhotoLink($row['ImageID'], $img);
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

    $sql = "SELECT PATH,Title,Description FROM travelimage  ORDER BY  RAND() LIMIT 0,6";
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


function Checklogin(){
   
    if(!isset($_SESSION['UserName'])){
        echo '<h3><a href="./doc/userlogin.php">Log In</a></h3>';
    }
    else{
        echo' <h3><a href="#">My Account</a></h3>';
        echo' <ul class="dropdown-menu">';
        echo' <li><i class="iconfont icon-shangchuan"></i><a href="./doc/upload.php">Upload</a></li>';
        echo' <li><i class="iconfont icon-picture"></i><a href="./doc/myPhoto.php">My Photo</a></li>';
        echo' <li><i class="iconfont icon-shoucang"></i><a href="./doc/myfavorite.php">My Favorite</a></li>';
        echo' <li><i class="iconfont icon-denglu"></i><a href="./doc/logout.php">Log Out</a></li>';
        echo' </ul>';
    }
}
?>

<?php
require_once('./config/config.php');

function select(){

    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if ( mysqli_connect_errno() ) {
        die( mysqli_connect_error() );
    }

    $sql = "select PATH from travelimage where ImageID = ''";
    $result = mysqli_query($connection,$sql);
    while ($rows = mysqli_fetch_assoc($result)) {
        echo $rows['PATH'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./css/reset.css" rel="stylesheet">
    <link href="./css/iconfont/iconfont.css" rel="stylesheet">
    <link href="./css/home.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="./js/home.js"></script>
    <script src="./js/dropdown.js"></script>
    <script type="text/javascript">

    </script>
    <title>home</title>
</head>
<body>
<header>
    <nav>
        <h3>
            <div class="div-left">
                <a href="index.php">
                    <img src="./img/logo1.png" width="50" alt="logo">
                </a>
                <a href="index.php" id="current">Home</a>
                <a href="./doc/browse.php">Browser</a>
                <a href="./doc/search.php">Search</a>
            </div>
        </h3>
        <div class="wrap">
            <?php Checklogin() ;?>
        </div>
    </nav>
</header>
<div class="content">
    <div class="picture1" onmouseenter="changeSrc()">
        <img src="./img/00002.jpg" width="100%" alt="website banner" id="lunbo">
    </div>
    <ul>
        <?php ImageID();?>
    </ul>
</div>
<div class="box">
    <div class="box1">
        <button type="button" name="arrow">
            <img src="./img/arrow.png" width="35px">
        </button><br>
    </div>
    <button type="button" id="demo" >
        <a href="./doc/alerthome.php"><img src="./img/repeat.png" width="35px"></a>
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
            <img src="./img/wechat.jpg" width="80px"/>
        </div>
    </div>
    <div class="copyright">
        <img src="./img/logo2.png" width="70px">
        <span class="copyrighttxt">Copyright © 2020 Alyssa. All Rights Reserved. 备案号：沪ICP备18307110513号</span>
    </div>
</footer>
</body>
</html>