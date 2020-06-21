<?php
require_once('../config/config.php');
error_reporting(0);
session_start();

$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
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
            <input type="text" name="title" required>
        </p>
        <p>
            <span class="name">Description:</span><br>
            <textarea name="desc" required></textarea>
        </p>
        <p>
            <span class="name">Content:</span><br>
            <select name="content" required>
                <option value="scenery">Scenery</option>
                <option value="city">City</option>
                <option value="people">People</option>
                <option value="animal">Animal</option>
                <option value="building">Building</option>
                <option value="wonder">Wonder</option>
                <option value="other">Other</option>
            </select>
        </p>
        <p>
            <span class="name">Country:</span><br>
            <input type="text" name="country" >
        </p>
        <p>
            <span class="name">City:</span><br>
            <input type="text" name="city" >
        </p>


        <p>
            <button type="submit">Upload
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


