<?php
require_once('../config/config.php');
error_reporting(0);
session_start();
$num_rec_per_page=5;
$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$start_from = ($page-1) * $num_rec_per_page;
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
    $rows = mysqli_fetch_assoc($result);
        $uid = $rows['UID'];

    return $uid;
}

$userid= findID($_SESSION['UserName']);


$sql="select travelimagefavor.ImageID,travelimage.PATH,travelimage.Title,travelimage.Description FROM travelimagefavor left join travelimage on travelimage.ImageID = travelimagefavor.ImageID where travelimagefavor.UID = '$userid'LIMIT $start_from, $num_rec_per_page";
$result = mysqli_query($connection, $sql);
?>

<?php

function outputSinglePhoto($row){
    echo '<div class="img">';
    $img = '<img src="../img/travel-images/medium/' . $row['PATH'] .'" class="cover">';
    echo constructPhotoLink($row['ImageID'], $img);
    echo '<h3><span class="title1">';
    echo constructPhotoLink($row['ImageID'], $row['Title']);
    echo '</span></h3>';
    echo '<div class="desc">';
    echo $row['Description'];
    echo '<span class="t">...</span>';
    echo '</div>';  // end class=content
    echo '<a href=javascript:del('.$row['ImageID'].')>';
    echo'<input type="button" value="Delete" name="delete">';
    echo '</a>';
    echo '</div>';
    echo '<hr>';
}

function pages(){
    $userid= findID($_SESSION['UserName']);

    $num_rec_per_page=5;
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

        $sql2 = "select travelimagefavor.ImageID,travelimage.PATH,travelimage.Title,travelimage.Description FROM travelimagefavor left join travelimage on travelimage.ImageID = travelimagefavor.ImageID where travelimagefavor.UID = '$userid'";
        $rs_result = mysqli_query($connection,$sql2);

    $total_records = mysqli_num_rows($rs_result);  // 统计总共的记录条数
    $total_pages = ceil($total_records / $num_rec_per_page);  // 计算总页数
 //   if($total_pages >5){
  //      $total_pages = 5;
  //  }
    echo "<a href='myfavorite.php?page=1'>".'|<'."</a> "; // 第一页

    for ($i=1; $i<=$total_pages; $i++) {
        echo "<a href='myfavorite.php?page=".$i."' class='num".$i."'>".$i."</a> ";
    };
    echo "<a href='myfavorite.php?page=$total_pages'>".'>|'."</a> "; // 最后一页
    echo'<style type="text/css">.num'.$_GET['page'].'{color: black;font-weight: bold;}</style>';
    if(!isset($_GET['page'])){
        echo'<style type="text/css">.num1{color: black;font-weight: bold;}</style>';
    }
}

function constructPhotoLink($id, $label) {

    $link = '<a href="detail.php?&id='.$id.'">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}

?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/reset.css" rel="stylesheet">
    <link href="../css/myfavorite.css" rel="stylesheet">
    <link href="../css/iconfont/iconfont.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../js/dropdown.js"></script>
    <script type="text/javascript">
        function del (id) {
            if (confirm("确定删除这张图片吗？")){
                window.location = 'delfavorite.php?id='+id;
            }
        }
    </script>
    <title>favor</title>
</head>
<body>
<header>
    <nav>
        <h3>
            <div class="div-left">
                <a href="../index.php">
                    <img src="../img/logo1.png" width="50">
                </a>
                <a href="../index.php">Home</a>
                <a href="./browse.php">Browser</a>
                <a href="./search.php">Search</a>
            </div>
        </h3>
        <div class="wrap">
            <h3><a href="#">My account</a></h3>
            <ul class="dropdown-menu">
                <li><i class="iconfont icon-shangchuan"></i><a href="./upload.php">Upload</a></li>
                <li><i class="iconfont icon-picture"></i><a href="./myPhoto.php">My Photo</a></li>
                <li><i class="iconfont icon-shoucang"></i><a href="./myfavorite.php">My Favorite</a></li>
                <li><i class="iconfont icon-denglu"></i><a href="./logout.php">Log Out</a></li>
            </ul>
        </div>
    </nav>
</header>
<div class="contents">
    <form method="get" name="favor" action="#">
        <div class="my_favorite">
            <div class="title">My Favorite</div>
            <hr>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                outputSinglePhoto($row);
            }
            if(!mysqli_num_rows($result) > 0){
                echo'<div class="notice">您还没有收藏照片，赶紧点击图片的收藏按钮增加一张吧！</div>';
            }
            ?>

        </div>
    </form>
    <div class="page">
        <?php  pages();?>
    </div>
</div>
<footer>
    <div class="copyright">
        <img src="../img/logo1.png" width="70px">
        <span class="copyrighttxt">Copyright © 2020 Alyssa. All Rights Reserved. 备案号：沪ICP备18307110513号</span>
    </div>
</footer>
</body>
</html>

