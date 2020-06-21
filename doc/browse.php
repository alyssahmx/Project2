<?php
require_once('../config/config.php');
error_reporting(0);
session_start();

$num_rec_per_page=9;
$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);



if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
$start_from = ($page-1) * $num_rec_per_page;

if(isset($_GET["content"])&&!isset($_GET["country"])) {
    $class= "content";
    $type = $_GET["content"];
    $sql = "select * from travelimage where Content = '$type'LIMIT $start_from, $num_rec_per_page";
    $sql2 = "select * from travelimage where Content ='$type'";
}
else if(isset($_GET["country"])&&!isset($_GET["content"])) {
    $class= "country";
    $type = $_GET["country"];
    $sql = "select travelimage.*,geocountries_regions.* from geocountries_regions left join travelimage on geocountries_regions.ISO = travelimage.Country_RegionCodeISO where Country_RegionName ='$type'LIMIT $start_from, $num_rec_per_page";
    $sql2 = "select travelimage.*,geocountries_regions.* from geocountries_regions left join travelimage on geocountries_regions.ISO = travelimage.Country_RegionCodeISO where Country_RegionName ='$type'";
}
else if(isset($_GET["city"])&&!isset($_GET["content"])) {
    $class= "city";
    $type = $_GET["city"];
    $sql = "select travelimage.*,geocities.* from geocities left join travelimage on travelimage.CityCode = geocities.GeoNameID where AsciiName ='$type' LIMIT $start_from, $num_rec_per_page";
    $sql2 = "select travelimage.*,geocities.* from geocities left join travelimage on travelimage.CityCode = geocities.GeoNameID where AsciiName ='$type'";
}

else if(isset($_POST['title']) or isset($_GET['title'])) {
    $class= "title";
    if(isset($_GET['title'])) {
        $type = $_GET['title'];
    }else{
        $type = $_POST['title'] ;
    }
    $title = '%' . $type. '%';
    $sql = "select * from travelimage where Title like '$title' LIMIT $start_from, $num_rec_per_page";
    $sql2 = "select * from travelimage where Title like '$title'";
}

else if(isset($_GET["city"])&&isset($_GET["content"])){
    if(isset($_GET['content'])) {
        $type = $_GET['content'];
    }else{
        $type = $_POST['title'] ;
    }
    if(isset($_GET['title'])) {
        $type = $_GET['title'];
    }else{
        $type = $_POST['title'] ;
    }
    if(isset($_GET['title'])) {
        $type = $_GET['title'];
    }else{
        $type = $_POST['title'] ;
    }


}else if(isset($_POST['Selectcontent'])){
     $content = $_POST['Selectcontent'];
     $city = $_POST['city'];
     $country = $_POST['country'];



     $sql3="select geocountries_regions.*,geocities.* from geocities left join geocountries_regions on geocountries_regions.ISO = geocities.Country_RegionCodeISO where geocities.AsciiName = '$city' and  geocountries_regions.Country_RegionName = '$country'";
     $res = mysqli_query($connection, $sql3);
     if(mysqli_num_rows($res) > 0){
         $rows = mysqli_fetch_assoc($res);
             $scity = $rows['GeoNameID'];
             $countryiso = $rows['Country_RegionCodeISO'];

             $sql = "select * from travelimage where Citycode='$scity' and Country_RegionCodeISO ='$countryiso' and Content= '$content' LIMIT $start_from, $num_rec_per_page";
             $sql2 = "select * from travelimage where Citycode='$scity' and Country_RegionCodeISO ='$countryiso' and Content= '$content'";
         }else{
         header("Refresh:1;url=browse.php?name=$userid");
     }




}

else {
    $sql = "select * from travelimage  ORDER BY RAND() LIMIT $start_from, $num_rec_per_page";
    $sql2 = "select * from travelimage ";
}

$result = mysqli_query($connection, $sql);
$rs_result = mysqli_query($connection, $sql2);

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

$total_records = mysqli_num_rows($rs_result);  // 统计总共的记录条数
$total_pages = ceil($total_records / $num_rec_per_page);  // 计算总页数

if($total_pages >5) {
    $total_pages = 5;
}

function constructPhotoLink($id, $label) {
    $link = '<a href="detail.php?id='.$id.'">';
    $link .= $label;
    $link .= '</a>';
    return $link;
}

function outputSinglePhoto($row){
    $img = '<img  src="../img/travel-images/medium/' . $row['PATH'] .'" class="cover">';
    echo constructPhotoLink($row['ImageID'], $img);
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
        echo' <li><i class="iconfont icon-shangchuan"></i><a href="./upload.php?name='.$_GET['name'].'">Upload</a></li>';
        echo' <li><i class="iconfont icon-picture"></i><a href="./myphoto.php?name='.$_GET['name'].'">My Photo</a></li>';
        echo' <li><i class="iconfont icon-shoucang"></i><a href="./myfavor.php?name='.$_GET['name'].'">My Favorite</a></li>';
        echo' <li><i class="iconfont icon-denglu"></i><a href="./logout.php">Log Out</a></li>';
        echo' </ul>';
    }
}

function Content(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
    $sql = "select t.Content from (select Content,count(*) counts from travelimage group by Content) t order by counts desc limit 5";

    $result = mysqli_query($connection, $sql);
    while ($rows = mysqli_fetch_assoc($result)) {
        $res = $rows['Content'];
        echo'<li><a href="browse.php?name='.$_GET['name'].'&content='.$res.'">'.$res.'</a></li>';
    }
}

function Country(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
    $sql = "select t.Country_RegionName from (select Country_RegionName,count(*) counts from travelimage left join geocountries_regions on geocountries_regions.ISO = travelimage.Country_RegionCodeISO group by Country_RegionName) t order by counts desc limit 6";
    $result = mysqli_query($connection, $sql);
    while ($rows = mysqli_fetch_assoc($result)) {
        $res = $rows['Country_RegionName'];
        echo'<li><a href="browse.php?name='.$_GET['name'].'&country='.$res.'">'.$res.'</a></li>';
    }
}

function FilterCountry(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
    $sql = "select t.Country_RegionName from (select Country_RegionName,count(*) counts from travelimage left join geocountries_regions on geocountries_regions.ISO = travelimage.Country_RegionCodeISO group by Country_RegionName) t order by counts desc";
    $result = mysqli_query($connection, $sql);
    while ($rows = mysqli_fetch_assoc($result)) {

        echo '<option value="' . $rows['Country_RegionName'] . '">';
        echo $rows['Country_RegionName'];
        echo "</option>";
    }
}

function City(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
    $sql = "select t.AsciiName from (select AsciiName,count(*) counts from travelimage left join geocities on travelimage.CityCode = geocities.GeoNameID group by AsciiName) t order by counts desc limit 6";
    $result = mysqli_query($connection, $sql);
    while ($rows = mysqli_fetch_assoc($result)) {
        $res = $rows['AsciiName'];
        echo'<li><a href="browse.php?name='.$_GET['name'].'&city='.$res.'">'.$res.'</a></li>';
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browser</title>
    <link href="../css/reset.css" rel="stylesheet">
    <link href="../css/browser.css" rel="stylesheet">
    <link href="../css/iconfont/iconfont.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../js/dropdown.js"></script>
    <script type="text/javascript" src="../js/search.js"></script>
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
                <a href="./browse.php" id="current">Browser</a>
                <a href="./search.php">Search</a>
            </div>
        </h3>
        <div class="wrap">
            <?php  Checklogin();?>
        </div>
    </nav>
</header>
<aside>
    <div class="search">
        <form method="post" action="browse.php">
        <input type="text" placeholder="Search by title" name="title">
        <button type="submit" name="search" >
            <i class="iconfont icon-sousuo"></i>
        </button>
        </form>
    </div>
    <div class="Hot">
        <div class="title">Hot Content</div>
        <hr>
        <ul >
            <?php Content();?>
        </ul>
    </div>
    <div class="Hot">
        <div class="title">Hot Country</div>
        <hr>
        <ul >
            <?php Country();?>
        </ul>
    </div>
    <div class="Hot">
        <div class="title">Hot City</div>
        <hr>
        <ul >
            <?php City();?>
        </ul>
    </div>
</aside>
<div class="contents">
    <div class="Filter">
        <div class="title1">Filter</div>
        <hr>
        <form method="post" action="browse.php">
            <label>Content</label>
            <select name="Selectcontent">
                <option value="scenery">Scenery</option>
                <option value="city">City</option>
                <option value="people">People</option>
                <option value="animal">Animal</option>
                <option value="building">Building</option>
                <option value="wonder">Wonder</option>
                <option value="other">Other</option>
            </select>
            <label>Country</label>
            <select name="country">
             <?php FilterCountry();?>
            </select>
            <label>City</label>
            <input type="text" placeholder="Search by city" name="city">

            <input type="submit" value="Filter">
        </form>
        <hr>
    </div>
    <div class="img">
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            outputSinglePhoto($row);
        }
        ?>
    </div>
    <div class="page">
        <?php
        echo "<a href='browse.php?$class=$type&page=1'>".'|<'."</a> "; // 第一页

        for ($i=1; $i<=$total_pages; $i++) {
            echo "<a href='browse.php?$class=$type&page=".$i."' class='num".$i."'>".$i."</a> ";
        };
        echo "<a href='browse.php?$class=$type&page=$total_pages'>".'>|'."</a> "; // 最后一页
        echo'<style type="text/css">.num'.$_GET['page'].'{color: black;font-weight: bold;}</style>';
        if(!isset($_GET['page'])){
            echo'<style type="text/css">.num1{color: black;font-weight: bold;}</style>';
        }
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
