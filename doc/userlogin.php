<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/reset.css" rel="stylesheet">
    <link href="../css/index.css" rel="stylesheet">
    <link href="../css/iconfont/iconfont.css" rel="stylesheet">
    <title>login</title>
</head>
<body>
<header>
    <img src="../img/logo1.png" width="90">
    <h3>Sign in</h3>
</header>
<section class="contents">


<?php
function getLoginForm(){
    return "
        <form method='post' action='' role='form'>
        <p>
      	  <span class=title>Username</span><br>
      	  <input type='text' name='username' placeholder='4-15位大小写字母或数字' required>
        </p>
        <p>
          <span class=title>Password</span><br>
          <input type='password' name='password' placeholder='6~16位大小写字母、数字、符号' required>	
        </p>   
        <p>
          <input type='submit' value='Sign in'>
        </p>  
      </form>";
}

function validLogin(){
    $pdo = new PDO(DBCONNSTRING,DBUSER,DBPASS);
    //very simple (and insecure) check of valid credentials.
    $sql = "SELECT * FROM traveluser WHERE UserName=:user and Pass=:pass";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user',$_POST['username']);
    $statement->bindValue(':pass',$_POST['password']);
    $statement->execute();
    if($statement->rowCount()>0){
        return true;
    }
    return false;
}

function findID($iname){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }

    $sql = "SELECT UID FROM traveluser where UserName = '$iname'";
    $result = mysqli_query($connection, $sql);
    while($rows = mysqli_fetch_assoc($result)) {
        $uid = $rows['UID'];
    }
    return $uid;
}

?>

    <?php
            require_once("../config/config.php");


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if(validLogin()){
                    // add 1 day to the current time for expiry time
            $expiryTime = time()+60*60*12;
            $_SESSION['UserName']=$_POST['username'];
        }
        else{
             echo '<span class="notice">请重试，用户名或密码错误</span>';
        }
    }

    if(isset($_SESSION['UserName'])){
        $username = findID($_SESSION['UserName']);
        header("Refresh:1;url=../index.php");
        echo "正在跳转至主页...";
    }
     if (!isset($_SESSION['UserName'])){
         echo getLoginForm();
     }

     ?>

    <br>
    <a href="./register.php">Create a new account?</a>
</section>
<footer>
    <div class="copyright">
        <img src="../img/logo1.png" width="70px">
        <span class="copyrighttxt">Copyright © 2020 Alyssa. All Rights Reserved. 备案号：沪ICP备18307110513号</span>
    </div>
</footer>
</body>
</html>
