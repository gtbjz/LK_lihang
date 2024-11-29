<?php
session_start(); // Start or resume the session

// 设置响应头以支持 JSON
header('Content-Type: application/json');

// 数据库连接
$servername = "localhost";
$username = "root";
$password = "root";

$conn = mysqli_connect($servername, $username, $password, 'myDB');
if (!$conn) {
    echo json_encode(['success' => false, 'message' => '数据库连接失败: ' . mysqli_connect_error()]);
    exit;
}

// 获取 AJAX 请求的输入数据
$request = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($request['id'])) { // 检查是否提供了 `id`
        $messageId = $request['id']; // 获取传递的留言 ID

        // 验证 messageId 是否为整数
        if (!filter_var($messageId, FILTER_VALIDATE_INT)) {
            echo json_encode(['success' => false, 'message' => '无效的留言 ID']);
            exit;
        }

        // 准备并执行删除语句
        $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
        $stmt->bind_param("i", $messageId); // `i` 表示整数类型
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => '删除成功']);
        } else {
            echo json_encode(['success' => false, 'message' => '删除失败或记录不存在']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => '无效请求，缺少留言 ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => '无效请求方法']);
}

// 关闭数据库连接
mysqli_close($conn);
?>
