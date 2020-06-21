<?php require_once('../config/config.php');

function check()
{
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

    if (mysqli_connect_errno()) {
        die(mysqli_connect_error());
    }
//查询语句，帮助协助查询当前注册用户名是否存在于数据库当中
    if(isset($_POST['Name'])) {
        $sql = "select * from traveluser where UserName='$_POST[Name]'";

//第一个'username'为数据库内已存在的username值，将其与第二个'POST'方法传递过来的username值做对比

        $rs = mysqli_query($connection, $sql);
        if (mysqli_num_rows($rs) > 0)//如果数据库内存在相同用户名，则'$rs'接收到的变量为'true'所以大于1为真，则返回'用户名已存在'
        {
            echo "<span class='notice'>用户名已存在，请重新注册！</span>";
//        header("Refresh:1;url=register.php");
        } else //否则可以成功注册递交
        {
            $sql = "INSERT INTO traveluser (UserName,Pass,Email) VALUES ('$_POST[Name]','$_POST[pword]','$_POST[email]')";
            if (!mysqli_query($connection, $sql)) {
                die('Error: ' . mysqli_error());
            }
           // echo "<span>注册成功！</span>";//显示注册成功信息
              header("Location:userlogin.php");
        }
    }else{
        echo"";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/reset.css" rel="stylesheet">
    <link href="../css/register.css" rel="stylesheet">
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="../js/register.js"></script>


    <title>register</title>
</head>
<body>
<header>
    <img src="../img/logo1.png" width="90">
    <h3>Sign up</h3>
</header>
<section>
    <form method="post" action="register.php">
        <p>
            <span class="title">Username</span><br>
            <input type="text" name="Name"  pattern="^[0-9a-zA-Z]{4,15}$" placeholder="4-15位大小写字母或数字" required ">
            <span id="show"></span>
        </p>
        <p>
            <span class="title">E-mail</span><br>
            <input type="email" name="email" pattern="^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*(\.\w{2,})+$" placeholder="请正确填写邮箱地址" required>
        </p>
        <p>
            <span class="title">Password</span><br>
            <input type="password" name="pword" id="pwd"  placeholder="6~16位大小写字母、数字、符号" required onkeyup="validate()">
            <span id="passstrength"></span>
        </p>
        <p>
            <span class="title">Confirm Your Password</span><br>
            <input type="password" name="repassword" id= "pwd1" placeholder="6~16位大小写字母、数字、符号" required onkeyup="compare()">
            <span id="tishi"></span>
        </p>
        <p>
            <?php echo check();?>
        </p>
        <p>
            <input type="submit" value="Sign up" id="submit">
        </p>
    </form>
</section>
<footer>
    <div class="copyright">
        <img src="../img/logo1.png" width="70px">
        <span class="copyrighttxt">Copyright © 2020 Alyssa. All Rights Reserved. 备案号：沪ICP备18307110513号</span>
    </div>
</footer>
</body>
</html>

