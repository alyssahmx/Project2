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
    while($rows = mysqli_fetch_assoc($result)) {
        $uid = $rows['UID'];
    }
    return $uid;
}

$userid= findID($_SESSION['UserName']);

$sql = "select * from travelimage where UID ='$userid' LIMIT $start_from, $num_rec_per_page";
$result = mysqli_query($connection, $sql);
?>

<?php
function outputSinglePhoto($row){
    echo '<div class="img">';
    $img = '<img  src="../img/travel-images/medium/' . $row['PATH'] .'" class="cover">';
    echo constructPhotoLink($row['ImageID'], $img);
    echo '<h3><span class="title1">';
    echo constructPhotoLink($row['ImageID'], $row['Title']);
    echo '</span></h3>';
    echo '<div class="desc">';
    echo $row['Description'];
    echo '<span class="t">...</span>';
    echo '</div>';  // end class=content
    echo '<a href=./upload.php?id='.$row['ImageID'].'><input type="button" value="Modify" id="modify"></a>';
    echo '<a href=javascript:del('.$row['ImageID'].')>';
    echo'<input type="button" value="Delete">';
    echo '</a>';
    echo '</div>';
    echo '<hr>';
}

function pages(){
    $userid= findID($_SESSION['UserName']);

    $num_rec_per_page=5;
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $sql2 = "select * from travelimage where UID = '$userid'";
    $rs_result = mysqli_query($connection,$sql2);

    $total_records = mysqli_num_rows($rs_result);  // 统计总共的记录条数
    $total_pages = ceil($total_records / $num_rec_per_page);  // 计算总页数
    if($total_pages >5){
        $total_pages = 5;
    }
    echo "<a href='myPhoto.php?page=1'>".'|<'."</a> "; // 第一页

    for ($i=1; $i<=$total_pages; $i++) {
        echo "<a href='myPhoto.php?page=".$i."' class='num".$i."'>".$i."</a> ";
    };
    echo "<a href='myPhoto.php?page=$total_pages'>".'>|'."</a> "; // 最后一页
    echo'<style type="text/css">.num'.$_GET['page'].'{color: black;font-weight: bold;}</style>';
    if(!isset($_GET['page'])){
        echo'<style type="text/css">.num1{color: black;font-weight: bold;}</style>';
    }
}

function constructPhotoLink($id, $label) {
    $link = '<a href="detail.php?id='.$id.'">';
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
    <link href="../css/myphoto.css" rel="stylesheet">
    <link href="../css/iconfont/iconfont.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../js/dropdown.js"></script>
    <script type="text/javascript">
        function del (id) {
            if (confirm("确定删除这张图片吗？")){
                window.location = 'delmyphoto.php?id='+id;
            }
        }
    </script>
    <title>my_photo</title>
</head>
<body>
<header>
    <nav>
        <h3>
            <div class="div-left">
                <a href="../index.php?name=<?php echo $_GET['name'];?>">
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
    <div class="my_photo">
        <div class="title">My Photo</div>
        <hr>


        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            outputSinglePhoto($row);
        }
        if(!mysqli_num_rows($result) > 0){
            echo'<div class="notice">您还没有上传照片，赶紧点击个人中心的上传按钮增加一张吧！</div>';
        }
        ?>

    </div>
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
</div>
</body>
</html>
