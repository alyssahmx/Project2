<?php
require_once('../config/config.php');
session_start();
//error_reporting(0);
/*
 Displays a list of genres
*/

try {
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'select travelimage.*,traveluser.* from traveluser left join travelimage on travelimage.UID = traveluser.UID where travelimage.ImageID=:id';
    $id =  $_GET['id'];
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $id);
    $statement->execute();

    $row = $statement->fetch(PDO::FETCH_ASSOC);
    $pdo = null;
}
catch (PDOException $e) {
    die( $e->getMessage() );
}

$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}
//查询语句，帮助协助查询当前注册用户名是否存在于数据库当中
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
$imageId = $_GET['id'];

$sql2 = "select travelimage.*,geocities.* from geocities left join travelimage on travelimage.CityCode = geocities.GeoNameID where ImageID=$imageId";

$result = mysqli_query($connection, $sql2);
$rows = mysqli_fetch_assoc($result);


$sql3 = "select geocountries_regions.*,travelimage.* from travelimage left join geocountries_regions on geocountries_regions.ISO = travelimage.Country_RegionCodeISO where ImageID= $imageId";
$results = mysqli_query($connection, $sql3);
$rs = mysqli_fetch_assoc($results);

?>
<?php
require_once('../config/config.php');
error_reporting(0);

function Checklogin(){
    $name= findID($_SESSION['UserName']);
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

function Favornum(){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
    $id = $_GET['id'];
    $sql = "SELECT COUNT(ImageID) FROM travelimagefavor where ImageID = $id";
    $result = mysqli_query($connection, $sql);
    $rows = mysqli_fetch_assoc($result);
    $values = array_values($rows);
    echo $values[0];
}


function addfavor()
{
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
//查询语句，帮助协助查询当前注册用户名是否存在于数据库当中
    $uid =  findID($_SESSION['UserName']);
    $imageId = $_GET['id'];
    $sql = "select UID, ImageID from travelimagefavor where ImageID=$imageId and UID=$uid";

    $result = mysqli_query($connection, $sql);
    $rows = mysqli_fetch_assoc($result);


    if (!$result) {
        printf("Error: %s\n", mysqli_error($connection));
        exit();
    }
        if (mysqli_num_rows($result) > 0)//如果数据库内存在相同用户名，则'$rs'接收到的变量为'true'所以大于1为真，则返回'用户名已存在'
        {
            $values = array_values($rows);
            $num = $values[0];
            echo "";

        } else //否则可以成功注册递交
        {
            $sql = "INSERT INTO travelimagefavor (UID,ImageID) VALUES ('$uid','$imageId')";
            if (!mysqli_query($connection, $sql)) {
                die('Error: ' . mysqli_error());
            }
            header("location:detail.php?id=$imageId&class=act");
        }

}



function checkfavor(){
   if (isset($_GET['class'])){
       echo "已收藏";
       addfavor();
   }
   else if(!isset($_GET['class'])&&!empty($_SESSION['UserName'])){
       echo"收藏";
   }else if(empty($_SESSION['UserName'])){
       echo "请登录";
   }
}
?>





<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/reset.css" rel="stylesheet">
    <link href="../css/details.css" rel="stylesheet">
    <link href="../css/iconfont/iconfont.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../js/dropdown.js"></script>
    <script src="../js/favor.js"></script>

    <title>details</title>
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
            <?php Checklogin() ;?>
        </div>
    </nav>
</header>
<div class="contents">
    <div class="title">Details</div>
    <hr>
    <div class="name">
        <span class="name1"><?php echo $row['Title']; ?></span>
        <span class="author"><?php echo $row['UserName']; ?></span>
    </div>
    <img src="../img/travel-images/medium/<?php echo $row['PATH']; ?>" alt="image" class="cover"></a>
    <div class="text1">
        <div class=likenumber>
            <p><span id="title2">Like Number</span></p>
            <hr>
            <p class="number"><?php Favornum(); ?></p>
        </div>
        <div class="imagedetails">
            <span id="title2">Image Details</span>
            <hr>
            <p>Content:  <?php echo $row['Content']; ?></p>
            <hr>
            <p>Country: <?php echo $rs['Country_RegionName']; ?> </p>
            <hr>
            <p>City:  <?php echo $rows['AsciiName']; ?> </p>
        </div>
        <div class="favor">

            <a href="./detail.php?id=<?php echo $_GET['id'] ;?>&class=act">
                <button type="button" id="demo"  onclick="change();bt_click()"><?php checkfavor();?>
                </button>
            </a>

        </div>
    </div>
    <div class="desc">
        &emsp;<?php echo $row['Description']; ?>
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
