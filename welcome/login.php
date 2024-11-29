<?php
session_start();

// 数据清理函数
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$nameError = $passwordError = "";
$name = $password = "";
$pword = 0;   
$nword = 0;

// 连接到数据库
$servername = "localhost";
$username = "root";
$password = "root";
$conn = mysqli_connect($servername, $username, $password, 'myDB');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 验证用户名
    if (empty($_POST["name"])) {
        $nameError = "名字是必须的";
    } else {
        $name = test_input($_POST["name"]);
        // 在数据库中查找
        $sql = "SELECT name FROM myguest WHERE name = '$name'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $nword = 1;
        } else {
            echo json_encode(['success' => false, 'message' => '用户不存在']);
            exit;
        }
    }

    // 验证密码
    if (empty($_POST["password"])) {
        $passwordError = "密码是必须的";
    } else {
        $password = test_input($_POST["password"]);
        $sql = "SELECT password FROM myguest WHERE password = '$password' AND name = '$name'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            $pword = 1;
        } else {
            echo json_encode(['success' => false, 'message' => '密码错误']);
            exit;
        }
    }

    // 如果用户名和密码均无错误，进行登录验证
    if (empty($nameError) && empty($passwordError) && $pword == 1 && $nword == 1) {
        // 页面重定向
        $_SESSION['username'] = $name; // 存储用户名到 session
        $_SESSION['userpassword'] = $password;
        header("Location:000.php");
    }
}
// 关闭连接
mysqli_close($conn);
?>
