<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

$getdat = file_get_contents("php://input");
$cobj = json_decode($getdat);
$user = $cobj -> user;

$dbhost = 'localhost:3306';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'tgy12345';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$conn) {
    die('连接失败: ' . mysqli_error($conn));
}
mysqli_query($conn,"set names utf8");
mysqli_select_db($conn,'fac');
$sql = "SELECT url FROM pic WHERE user='$user'";
$retval = mysqli_query($conn, $sql);
if (!$retval) {
  echo mysqli_error($conn);
}
else {
  $val = mysqli_fetch_assoc($retval);
  echo $val['url'];
}


mysqli_close($conn);


?>