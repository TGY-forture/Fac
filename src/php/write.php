<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

$getdat = file_get_contents("php://input");
$cobj = json_decode($getdat);
$log = $cobj -> seq;

$dbhost = 'localhost:3306';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'tgy12345';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$conn) {
    die('连接失败: ' . mysqli_error($conn));
}
mysqli_query($conn,"set names utf8");
mysqli_select_db($conn,'fac');
$sql = "SELECT * FROM protab WHERE pro_seq='$log'";
$retp = mysqli_query($conn,$sql);
$guser = mysqli_fetch_assoc($retp);
if ($guser['pro_seq']) {
  echo json_encode($guser);
}
else {
  $sql = "INSERT INTO protab (pro_seq) VALUES ('$log')";
  $inval = mysqli_query($conn, $sql);
  if (!$inval) {
    echo "错误：" . mysqli_error($conn);
  }
  $sql = "SELECT * FROM protab WHERE pro_seq='$log'";
  $retp = mysqli_query($conn,$sql);
  echo json_encode(mysqli_fetch_assoc($retp));
}


mysqli_close($conn);

?>