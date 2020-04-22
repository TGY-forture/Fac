<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

$getdat = file_get_contents("php://input");
$cobj = json_decode($getdat);

$seq = $cobj -> pro_seq;
$p_name = $cobj -> pro_name;
$p_time = $cobj -> pro_time;
$p_count = $cobj -> pro_count;
$m_name = $cobj -> mat_name;
$process_name = $cobj -> process_name;
$qu_name = $cobj -> qual_name;

$dbhost = 'localhost:3306';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'tgy12345';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$conn) {
    die('连接失败: ' . mysqli_error($conn));
}
mysqli_query($conn,"set names utf8");
mysqli_select_db($conn,'fac');
$sql = "UPDATE protab SET pro_name='$p_name',pro_time='$p_time',pro_count='$p_count',".
  "mat_name='$m_name',process_name='$process_name',qual_name='$qu_name' WHERE pro_seq='$seq'";
$retval = mysqli_query($conn,$sql);
if (!$retval) {
  echo "错误：" . mysqli_error($conn);
}
else {
  echo 'success';
}

mysqli_close($conn);

?>