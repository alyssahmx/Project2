
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
    switch($_POST['radio']){
        case "title":
            if (isset($_GET['title'])) {
                $title = '%' . $_GET['title'] . '%';

            } else {
                $select = $_POST['title'];
                $title = '%' . $_POST['title'] . '%';

            }
            $sql = "select * from travelimage where Title like '$title' LIMIT $start_from, $num_rec_per_page";
            $result = mysqli_query($connection, $sql);
            break;
    case "description":
    if (isset($_GET['desc'])) {
        $desc = '%' . $_GET['desc'] . '%';

    } else {
        $desc = '%' . $_POST['description'] . '%';
    }
        $sql = "select * from travelimage where Description like '$desc' LIMIT $start_from, $num_rec_per_page";
        $result = mysqli_query($connection, $sql);
   break;
        default:
            $sql = "select * from travelimage order by Rand() LIMIT $start_from, $num_rec_per_page";
            $result = mysqli_query($connection, $sql);
}



function pages(){

    if(isset($_GET['title'])) {
        $searchtitle = $_GET['title'];
    }else{
        $searchtitle = $_POST['title'];;
    }
    if(isset($_GET['desc'])) {
        $searchdesc = $_GET['desc'];
    }else{
        $searchdesc = $_POST['description'];
    }
    $num_rec_per_page=5;
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    switch($_POST['radio'] ){
    case "title":
        $title = '%' . $searchtitle . '%';
        $sql2 = "select * from travelimage where Title like '$title'";
        $rs_result = mysqli_query($connection,$sql2);
        break;
    case"description":
        $desc = '%' . $searchdesc . '%';
        $sql2 = "select * from travelimage where Description like '$desc'";
        $rs_result = mysqli_query($connection,$sql2);
        break;
        default:
            $sql2 = "select * from travelimage order by Rand() ";
            $rs_result = mysqli_query($connection, $sql2);
    }
    $total_records = mysqli_num_rows($rs_result);  // 统计总共的记录条数
    $total_pages = ceil($total_records / $num_rec_per_page);  // 计算总页数
    if($total_pages >5){
        $total_pages = 5;
    }
    echo "<a href='search.php?&title=$searchtitle&desc=$searchdesc&page=1'>".'|<'."</a> "; // 第一页

    for ($i=1; $i<=$total_pages; $i++) {
        echo "<a href='search.php?title=$searchtitle&desc=$searchdesc&page=".$i."' class='num".$i."'>".$i."</a> ";
    };
    echo "<a href='search.php?title=$searchtitle&desc=$searchdesc&page=$total_pages'>".'>|'."</a> "; // 最后一页
    echo'<style type="text/css">.num'.$_GET['page'].'{color: black;font-weight: bold;}</style>';
    if(!isset($_GET['page'])){
        echo'<style type="text/css">.num1{color: black;font-weight: bold;}</style>';
    }
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
    echo '</div>';
    echo '<hr>';
}

function constructPhotoLink($id, $label) {
    $link = '<a href="detail.php?&id='.$id.'">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}

function Checklogin(){
    if(isset($_SESSION['UserName'])){
        $name = $_SESSION['UserName'];}
    if($name == null){
        echo '<h3><a href="./userlogin.php">Log In</a></h3>';
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
    <link href="../css/search.css" rel="stylesheet">
    <link href="../css/iconfont/iconfont.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../js/dropdown.js"></script>

    <title>Search</title>
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
                <a href="./search.php" id="current">Search</a>
            </div>
        </h3>
        <div class="wrap">
            <?php Checklogin() ;?>
        </div>
    </nav>
</header>
<div class="content1">
    <div class="title">Search</div>
    <hr>
    <form method="post" name="fliter" action="search.php" checked>
        <div class="input">

            <input type="radio" name="radio" value="title" checked>Filter by Title<br>
            <input type="text" name="title"><br>
            <input type="radio" name="radio" value="description">Filter by Description<br>
            <textarea name="description"></textarea><br>
            <input type="submit" value="Filter">

        </div>
    </form>
</div>
<div class="content2">
    <div class="result">
        <div class="title">Result</div>
        <hr>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            
            outputSinglePhoto($row);
        }
        ?>

    </div>
    <div class="page">

        <?php
         pages();
        ?>

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

