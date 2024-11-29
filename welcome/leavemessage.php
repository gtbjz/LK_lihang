<?php
session_start(); // Start or resume the session

$messError = "";
$mess = "";
$messword = "";  
//########### 连接到数据库#####################################
$servername = "localhost";
$username = "root";
$password = "root";

$conn = mysqli_connect($servername, $username, $password, 'myDB');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
//#############################################################
if (isset($_SESSION['username'])) {
    // Retrieve and display the username
    //echo "Welcome, " . $_SESSION['username'];
    $name = $_SESSION['username'];
} else {
    echo "No session value found for username.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // 验证留言是否为空
    if (empty($_POST["message"])) {
        $messError = "留言不能为空！";
    } 
    else 
    {
        $mess = test_input($_POST["message"]);
        //在数据库中查找
        $sql = "INSERT INTO messages(name,words) VALUES ('$name','$mess')";
            if($conn->query($sql)===TRUE)
            {
                echo "<script>alert('留言成功！');</script>";
                header("Location: login.html");
            }
            else
            {
                echo "Error:". $sql ."<br>"  .mysqli_error($conn);
            }
            }
}
// 数据清理函数
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;}
// 关闭连接
mysqli_close($conn);
?>