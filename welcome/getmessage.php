<?php
session_start(); // Start or resume the session

// 设置响应头以支持 JSON
header('Content-Type: application/json');

// 连接数据库
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "myDB";  // 确保数据库名称正确

// 创建数据库连接
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 检查连接
if (!$conn) {
    die(json_encode([
        'success' => false,
        'message' => '连接数据库失败: ' . mysqli_connect_error()
    ]));
}

// 处理 POST 请求 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 查询数据
    $sql = "SELECT id, name, words, reg_date FROM messages ORDER BY reg_date DESC";
    
    // 执行查询
    $result = mysqli_query($conn, $sql);
    
    // 检查查询是否成功
    if ($result) {
        $messages = []; // 用于存储结果集

        // 遍历数据并存储到数组
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = $row;
        }

        // 返回 JSON 格式的结果
        echo json_encode([
            "success" => true,
            "data" => $messages
        ]);
    } else {
        // 如果查询失败，返回 JSON 格式的错误信息
        echo json_encode([
            "success" => false,
            "message" => "查询失败: " . mysqli_error($conn)
        ]);
    }
} else {
    // 如果不是 POST 请求，返回错误信息
    echo json_encode([
        "success" => false,
        "message" => "无效请求方法"
    ]);
}

// 关闭数据库连接
mysqli_close($conn);
?>
