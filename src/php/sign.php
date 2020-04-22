<?php
// header("Content-Type: text/html; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

$getdat = file_get_contents("php://input");
$cobj = json_decode($getdat);

$dbhost = 'localhost:3306';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'tgy12345';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$conn) {
    die('连接失败: ' . mysqli_error($conn));
}

$user = $cobj -> user;
$password = $cobj -> password;
$email = $cobj -> email;

// 设置编码，防止中文乱码
mysqli_query($conn, "set names utf8");
$getsql = "SELECT user FROM usertab";
mysqli_select_db( $conn, 'fac' );
$gret = mysqli_query($conn,$getsql);
while ($userc = mysqli_fetch_array($gret,MYSQLI_ASSOC)) {
    if ($userc['user'] == $user) {
        echo "exist";
    break;
    }
}

if ($userc == NULL) {
    $sql = "INSERT INTO usertab (user,password,email)".
           "VALUES ('$user','$password','$email')";
    $retval = mysqli_query( $conn, $sql );

    $sql = "INSERT INTO pic (user) VALUES ('$user')";
    $val = mysqli_query($conn, $sql);

    if(!$retval || !$val) {
        die('无法插入数据: ' . mysqli_error($conn));
    }
    else {
      echo "success";
    }
}
mysqli_close($conn);
