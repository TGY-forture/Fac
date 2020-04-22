<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

// $getdat = file_get_contents("php://input");

$file = $_FILES['pic']['tmp_name'];
$binfile = fopen($file, 'rb');
$binstr = fread($binfile, filesize($file));
$user = $_POST['user'];

$dbhost = 'localhost:3306';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'tgy12345';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$conn) {
    die('连接失败: ' . mysqli_error($conn));
}
mysqli_query($conn,"set names utf8");
mysqli_select_db($conn,'fac');
$bin = mysqli_real_escape_string($conn, $binstr);

$sql = "UPDATE pic SET url='$bin' WHERE user='$user'";
$val = mysqli_query($conn,$sql);
if (!$val) {
  echo mysqli_error($conn);
}
else {
  echo "success";
}

mysqli_close($conn);

?>