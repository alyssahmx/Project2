<?php
require_once('../config/config.php');
//error_reporting(0);
session_start();

$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}
if(isset($_GET['id'])) {
    $imageid = $_GET['id'];
    $sql = "select travelimage.*,geocountries_regions.* from travelimage left join geocountries_regions on travelimage.Country_RegionCodeISO = geocountries_regions.ISO where travelimage.ImageID ='$imageid'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    $getCitySql="select travelimage.*,geocities.* from geocities left join travelimage on travelimage.CityCode = geocities.GeoNameID where travelimage.ImageID ='$imageid'";
    $query = mysqli_query($connection, $getCitySql);
    $rs = mysqli_fetch_assoc($query);
}

function imgPath(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }

    if(isset($_GET['id'])){
        $imageid = $_GET['id'];
        $sql="select * from travelimage where ImageID ='$imageid'";
        $result = mysqli_query($connection, $sql);
        $row = mysqli_fetch_assoc($result);
        echo '<img src="../img/travel-images/medium/'.$row['PATH'].'">';
    }else{
        echo"";
    }

    function FilterCountry(){
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

        if (mysqli_connect_errno()) {
            die(mysqli_connect_error());
        }
        $sql = "select Country_RegionName from geocountries_regions order by Population desc limit 0,1000";
        $result = mysqli_query($connection, $sql);
        while ($rows = mysqli_fetch_assoc($result)) {

            echo '<option value="' . $rows['Country_RegionName'] . '">';
            echo $rows['Country_RegionName'];
            echo "</option>";
        }
    }

}
function check(){
$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}

    $imageid = $_GET['id'];
    $sql="select * from travelimage where ImageID ='$imageid'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    if(isset($_GET['id'])){
        echo'<form method="post" action="./update.php?id='.$_GET['id'].'">';

    }else if(!isset($_GET['id'])){
        echo'<form method="post" action="./insert.php">';
    }
}

function update(){
if(isset($_GET['id'])) {
    echo "Update";
}else{
    echo "Upload";
    }

}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/reset.css" rel="stylesheet">
    <link href="../css/upload.css" rel="stylesheet">
    <link href="../css/iconfont/iconfont.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../js/upload.js"></script>
    <script src="../js/dropdown.js"></script>
    <script src="../js/alert.js"></script>
    <title>upload</title>
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
<div class="content">
    <span class="title">Upload</span>
    <hr>
    <?php echo check();?>
    <div class="upload">
        <div class="showImg" id="showImg">
            <?php echo imgPath();?>
        </div>
        <input type="file" name="path" id="imgWrap" accept="*.jpg,*.jpeg,*.png,*.gif">
    </div>
    <div class="text">

        <p>
            <span class="name">Title:</span><br>
            <textarea  name="title" id="title" required><?php echo $row['Title'];?></textarea>
        </p>
        <p>
            <span class="name">Description:</span><br>
            <textarea name="desc" required><?php echo $row['Description'];?></textarea>
        </p>
        <p>
            <span class="name">Content:</span><br>
            <select name="content" required>
                <?php echo'<option value='.$row['Content'] .'>'.$row['Content'].'</option>';?>
                <option value="scenery">scenery</option>
                <option value="city">city</option>
                <option value="people">people</option>
                <option value="animal">animal</option>
                <option value="building">building</option>
                <option value="wonder">wonder</option>
                <option value="other">other</option>
            </select>
        </p>
        <p>
            <span class="name">Country:</span><br>
            <select name="country" required>
                <?php echo'<option value='.$row['Country_RegionName'] .'">'.$row['Country_RegionName'].'</option>';?>
                <?php FilterCountry();?>
            </select>
        </p>
        <p>
            <span class="name">City:</span><br>
            <textarea id="citytext" name="city" required><?php echo $rs['AsciiName'];?>
            </textarea>
        </p>


        <p>
            <button type="submit"><?php update();?>
            </button>
        </p>

    </div>
    </form>

</div>
<footer>
    <div class="copyright">
        <img src="../img/logo1.png" width="70px">
        <span class="copyrighttxt">Copyright © 2020 Alyssa. All Rights Reserved. 备案号：沪ICP备18307110513号</span>
    </div>
</footer>
</body>
</html>


