<?php
// ###### 初始化错误消息变量和输入变量 ###########################
$nameError = $passwordError = $emailError = "";
$name = $password = $email = "";
$nword = $pword = $eword = 0;

// ########### 连接到数据库 #####################################
$servername = "localhost";
$username = "root";
$password = "root";
$conn = mysqli_connect($servername, $username, $password, 'myDB');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
// 检查是否为 POST 请求
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // ######## 验证用户名 #####################
    if (empty($_POST["name"])) {
        $nameError = "名字是必须的";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameError = "只允许字母和空格";
        } else {
            $nword = 1; // 用户名验证通过
        }
    }
    // ############### 验证密码 #########################
    if (empty($_POST["password1"]) && empty($_POST["password2"])) {
        $passwordError = "密码是必须的";
    } else if ($_POST["password1"] != $_POST["password2"]) 
    {
        $passwordError = "两次密码不一致！";
        echo "<script>alert('两次密码不一致！');</script>";
    } else {
        $password = test_input($_POST["password1"]);
        if (!check_password($password)) {
            $passwordError = "密码不符合要求!";
        } else {
            $pword = 1; // 密码验证通过
        }
    }
    // ################### 验证邮箱 #######################
    if (empty($_POST["email"])) {
        $emailError = "邮箱不能为空";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "非法邮箱格式";
        } else {
            $eword = 1; // 邮箱验证通过
        }
    }
    // ################# 数据插入数据库 #####################
    if (empty($nameError) && empty($passwordError) && empty($emailError) && $nword == 1 && $pword == 1 && $eword == 1) {
        // 使用 Prepared Statement 防止 SQL 注入
        $stmt = $conn->prepare("INSERT INTO myguest (name, password, email) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sss", $name, $password, $email);

        if ($stmt->execute()) {
            echo "<script>alert('注册成功！');</script>";
            header("Location: start.html"); 
            
        } else {
            echo "Error: " . $stmt->error;
        }

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

// 密码检查函数
function check_password($password) {
    // 验证密码是否符合要求
    $hasInitialLetter = preg_match("/[a-zA-Z]/", $password); // 必须包含字母
    $hasNumber = preg_match("/\d/", $password);             // 必须包含数字
    $hasSpecialChar = preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password); // 必须包含特殊字符
    $isValidLength = strlen($password) >= 6 && strlen($password) <= 18;

    return $hasInitialLetter && $hasNumber && $hasSpecialChar && $isValidLength;
}

// 关闭连接
mysqli_close($conn);
?>
