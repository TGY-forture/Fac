<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE');

$getdat = file_get_contents("php://input");
$cobj = json_decode($getdat);

$sql;
if (isset($cobj -> p_count)) {
  $lcnt = $cobj -> p_count;
  $sql = "SELECT * FROM protab WHERE pro_count='$lcnt'";
  if (isset($cobj -> p_year)) {
    $lyear = $cobj -> p_year;
    $sql = $sql . " AND pro_time LIKE '$lyear%'";
    if (isset($cobj -> p_month)) {
      $lmon = $cobj -> p_month;
      if ($lmon >= 10) {
        $sql = substr_replace($sql, "-$lmon", 71, 0);
      }
      else {
        $sql = substr_replace($sql, "-$lmon-", 71, 0);
      }
    }
  }
  else if (isset($cobj -> p_month)) {
    $lmon = $cobj -> p_month;
    if ($lmon >= 10) {
      $sql = $sql . " AND pro_time LIKE '____-$lmon%'";
    }
    else {
      $sql = $sql . " AND pro_time LIKE '____-$lmon-%'";
    }
  }
}
else if (isset($cobj -> p_year)) {
  $lyear = $cobj -> p_year;
  $sql = "SELECT * FROM protab WHERE pro_time LIKE '$lyear%'";
  if (isset($cobj -> p_month)) {
    $lmon = $cobj -> p_month;
    if ($lmon >= 10) {
      $sql = substr_replace($sql, "-$lmon", 46, 0);
    }
    else {
      $sql = substr_replace($sql, "-$lmon-", 46, 0);
    }
  }
}
else if (isset($cobj -> p_month)) {
  $lmon = $cobj -> p_month;
  if ($lmon >= 10) {
    $sql = "SELECT * FROM protab WHERE pro_time LIKE '____-$lmon%'";
  }
  else {
    $sql = "SELECT * FROM protab WHERE pro_time LIKE '____-$lmon-%'";
  }
}


$dbhost = 'localhost:3306';  // mysql服务器主机地址
$dbuser = 'root';            // mysql用户名
$dbpass = 'tgy12345';          // mysql用户名密码
$conn = mysqli_connect($dbhost, $dbuser, $dbpass);
if (!$conn) {
    die('连接失败: ' . mysqli_error($conn));
}
mysqli_query($conn,"set names utf8");
mysqli_select_db($conn,'fac');
$retval = mysqli_query($conn, $sql);

$out = array();
for ($i = 0; $dat = mysqli_fetch_assoc($retval); $i++) {
  $out[$i] = $dat;
}
if (count($out) == 0) {
  echo 'No data!';
}
else {
  echo json_encode($out);
}


mysqli_close($conn);

?>