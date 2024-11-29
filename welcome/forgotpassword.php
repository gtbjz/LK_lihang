<?php
$nameError  = $email = "";
$name  = $email = "";
$nword =0; $eword = 0;
//########### 连接到数据库#####################################
$servername = "localhost";
$username = "root";
$password = "root";

$conn = mysqli_connect($servername, $username, $password, 'myDB');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
else {
    echo "连接成功！<br>";
}
//#######################################################
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 验证用户名
    if (empty($_POST["name"])) {
        $nameError = "名字是必须的";
    } 
    else 
    {
        $name = test_input($_POST["name"]);
        //在数据库中查找
        $sql = "SELECT name FROM myguest WHERE name = '$name'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) 
            {
                while ($row = mysqli_fetch_assoc($result)) {
                    $nword  = 1;
                }
            }
             else 
            {
                echo "没有找到用户";
            }
    }
    // 验证邮箱
    if (empty($_POST["email"])) {
        $emailError = "邮箱是必须的";
    } 
    else
    {
        $email = test_input($_POST["email"]);
        $sql = "SELECT email FROM myguest WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) 
            {
                while ($row = mysqli_fetch_assoc($result)) {
                    $eword = 1;
                }
            }
             else 
            {
                echo "邮箱不存在";
            }
    }
    // 如果用户名和邮箱均无错误，返回密码；
    if (empty($nameError) && empty($emailError) && $eword ==1 && $nword==1 ) 
    {
        $name = $_POST['name'] ?? ''; 
        // 防止SQL注入，准备SQL语句
        $stmt = $conn->prepare("SELECT password FROM myguest WHERE name = ? AND email = ?");
        $stmt->bind_param("ss", $name,$email); // 使用占位符绑定用户输入

        // 执行SQL查询
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "名字：$name 的密码是： " . $row['password'] . "<br>";
            }
        } else {
            echo "没有找到名字为 $name 的用户。";
}
// 关闭连接
$stmt->close();
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
