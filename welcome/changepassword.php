<?php
session_start(); // Start or resume the session
$passwordError  = "";
$pword = 0;
//########### 连接到数据库#####################################
$servername = "localhost";
$username = "root";
$password = "root";

$conn = mysqli_connect($servername, $username, $password, 'myDB');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
//#######################################################
if (isset($_SESSION['username'])) {
    // Retrieve and display the username
    //echo "Welcome, " . $_SESSION['username'];
    $name = $_SESSION['username'];
} else {
    echo "No session value found for username.";
}
//##########################################################
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 验证用户名
    if (empty($_POST["password1"]) && empty($_POST["password2"])) {
        $passwordError = "密码不能为空";
        echo "1111";
    } 
    else if('{$_POST["password1"]}' == '{$_POST["password2"]}')
    {
        $passwordError = "不能与原始密码相同";
    } 
    else 
    {
        $password = test_input($_POST["password2"]);
        $pword = 1;

    }
    if (empty($passwordError)  && $pword ==1  ) 
    {
        $sql = "SELECT id FROM myguest WHERE name = '$name' AND password='{$_POST['password1']}'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) 
            {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id']; // 提取 id
                    $update_sql = "UPDATE myguest SET password = '{$_POST['password2']}' WHERE id = $id";
                    if (mysqli_query($conn, $update_sql)) {
                        echo "<script>alert('修改成功！');</script>";
                        $_SESSION['username'] = $name; // 存储用户名到 session
                        header("Location: 000.php"); 
                    } else {
                        echo "密码更新失败：" . mysqli_error($conn);
                    }
                }
            }
    }
}
// 数据清理函数
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
// 关闭连接
mysqli_close($conn);
?>
