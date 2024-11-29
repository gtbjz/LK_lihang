<?php
session_start();

// 连接到数据库
$servername = "localhost";
$username = "root";
$pword = "root";

$conn = mysqli_connect($servername, $username, $pword, 'myDB');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// 处理 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 数据中获取用户名和密码
    if (isset($_SESSION['username'])) {
        $name = $_SESSION['username'];
    } 
    if (isset($_SESSION['userpassword'])) {
        $password = $_SESSION['userpassword'];
    } 
    //echo "$name,$password";
    // 检查用户是否存在
    $sql = "SELECT * FROM myguest WHERE name = '$name' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // 用户存在，执行删除
        $delete_sql = "DELETE FROM myguest WHERE name = '$name' AND password = '$password'";
        if (mysqli_query($conn, $delete_sql)) {
            echo json_encode(['success' => true, 'message' => '注销成功']);
        }
        else {
            echo json_encode(['success' => false, 'message' => '注销失败，请重试']);
        }
    } 
    else {
            echo json_encode(['success' => false, 'message' => '用户不存在或密码错误']);
        }
} 
else {
    // 非 POST 请求的处理
    echo json_encode(['success' => false, 'message' => '无效请求']);
}

// 关闭连接
mysqli_close($conn);
?>
