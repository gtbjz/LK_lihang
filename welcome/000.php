<?php
session_start();  // 启动会话
$name = $_SESSION['username'];
//echo "$name";
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>留言板</title>
<style>
    /* 全局样式 */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        height: 100vh;
        background: url('test.jpg') no-repeat center center/cover;
        font-family: Arial, sans-serif;
        color: #333;
    }
    h2 {
        font-size: 2.5rem;
        text-align: center;
        color: #3c235d;
        margin: 0;
        padding: 20px 0;
        border-bottom: 4px solid rgb(168, 210, 54);
        width: 100%;
        text-shadow: 1px 1px 2px rgba(128, 0, 21, 0.6);
    }
    .nav {
        width: 100%;
        background-color: #d28d1f;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
    }
    .nav ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
    }
    .nav li {
        margin: 0;
    }
    .nav a:link, .nav a:visited {
        display: block;
        padding: 8px 15px;
        font-weight: bold;
        color: #FFFFFF;
        text-decoration: none;
        text-transform: uppercase;
        background-color: #d28d1f;
    }
    .nav a:hover, .nav a:active {
        background-color: #7A991A;
    }
    /* 用户信息和水平导航栏样式 */
    .user-menu {
        position: relative;
        display: inline-block;
        color: #fff;
        font-weight: bold;
        cursor: pointer;
    }
    .user-menu:hover .dropdown {
        display: block;
    }
    .user-menu span {
        padding: 8px 15px;
        background-color: #d28d1f;
        border-radius: 5px;
        text-transform: capitalize;
    }
    .dropdown {
        display: none;
        position: absolute;
        right: 0;
        background-color: #fff;
        color: #333;
        min-width: 150px;
        border: 1px solid #ccc;
        box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 5px;
    }
    .dropdown a {
        color: #333;
        text-decoration: none;
        display: block;
        padding: 10px 15px;
    }
    .dropdown a:hover {
        background-color: #f0f0f0;
    }
    .content {
        width: 90%;
        max-width: 600px;
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 100px 0;
    }
    .content p {
        font-size: 1.2rem;
        margin-bottom: 15px;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    textarea, input[type="submit"] {
        width: 100%;
        font-size: 1rem;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    textarea {
        resize: none;
        height: 100px;
    }
    input[type="submit"] {
        background: #2b5d23;
        color: white;
        cursor: pointer;
        border: none;
        transition: background 0.3s;
    }
    input[type="submit"]:hover {
        background: #368f34;
    }
    /* 留言展示样式 */
    .messages-container {
        width: 90%;
        max-width: 600px;
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin: 20px 0;
    }
    .messages-container h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
    }
    #messages-list {
        list-style-type: none;
        padding: 0;
    }
    .message-item {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background: #fff;
    }
    .message-item p {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }
    .message-actions {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
    }
    .message-actions button {
        font-size: 0.9rem;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .reply-btn {
        background: #3b5998;
        color: white;
    }
    .like-btn {
        background: #55acee;
        color: white;
    }
    .delete-btn {
        background: #e74c3c;
        color: white;
    }
</style>
</head>
<body>
<h2>欢迎来到留言板</h2>
<!--水平导航栏-->
<div class="nav">
    <ul>
        <li><a href="#news">个人中心</a></li>
        <li><a href="#about">设置</a></li>
    </ul>
    <div class="user-menu">
        <span id="username" ></span>
            <script>
                // 动态设置用户名
                let name = "<?php echo $name; ?>";  // PHP 的 $name 传递给 JavaScript 变量 name
                document.getElementById('username').textContent = `欢迎，${name}`;
            </script>
        <div class="dropdown">
            <a href="#profile" onclick="changePassword()">修改密码</a>
            <a href="#logout" onclick="logout()">退出登录</a>
            <a href="#" onclick="deleteuser()">注销用户</a>
        </div>
    </div>
</div>
<div class="content">
    <p>请在下方填写您的留言内容：</p>
    <form action="leavemessage.php" method="POST">
        <textarea name="message" placeholder="请输入留言内容..."></textarea>
        <input type="submit" value="提交留言">
    </form>
</div>
<!-- 留言展示窗口 -->
<div class="messages-container">
    <h3>留言列表</h3>
    <ul id="messages-list">
        <!-- 留言内容会动态插入这里 -->
    </ul>
</div>
<script>
    //***************用户下拉列表**********************/
    function changePassword() 
    {
        window.location.href = "changepassword.html";
    }
    function logout() {
        window.location.href = "start.html";
        window.alert("已退出登录！") ;
    }
    function deleteuser() {
        // 弹出确认对话框
        if (confirm('确定要注销吗？')) {
            // 使用 Fetch API 发起 POST 请求
            fetch('deleteuser.php', {
                method: 'POST', // 指定 HTTP 请求方法为 POST
                headers: {
                    'Content-Type': 'application/json' // 设置请求头，表明发送 JSON 数据
                }
            })
            .then(response => response.json()) // 解析 JSON 响应
            .then(data => {
                if (data.success) {
                    // 注销成功
                    alert(data.message);
                    window.location.href = 'start.html'; // 跳转到登录页面
                } else {
                    // 注销失败
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('请求失败:', error);
                alert('注销失败，请检查网络或稍后重试。');
            });
        }
    }
    // *********************模拟留言数据**************************//
     // 动态加载留言数据
    function loadMessages() {
        fetch('getmessage.php', {
            method: 'POST', // 指定 HTTP 请求方法为 POST
            headers: { 'Content-Type': 'application/json' } // 设置请求头，表明发送 JSON 数据
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messagesList = document.getElementById('messages-list');
                messagesList.innerHTML = ""; // 清空内容

                // 遍历数据并插入 HTML
                data.data.forEach(message => {
                    const li = document.createElement('li');
                    li.className = 'message-item';
                    li.innerHTML = `
                        <strong>${message.name}</strong>
                        <p>${message.words}</p>
                        <small>${message.reg_date}</small>
                        <div class="message-actions">
                            <button class="reply-btn" onclick="replyMessage(${message.id})">回复</button>
                            <button class="delete-btn" onclick="deleteMessage(${message.id})">删除</button>
                        </div>
                    `;
                    messagesList.appendChild(li);
                });
            } else {
                console.error('加载留言失败:', data.message);
            }
        })
        .catch(error => console.error('请求失败:', error));
        }

    // 删除留言
    function deleteMessage(messageId) {
    if (confirm('确定要删除这条留言吗？')) {
        fetch('deletemessage.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: messageId }) // 发送留言的 ID
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('删除成功！');
                loadMessages(); // 重新加载留言
            } else {
                alert('删除失败：' + data.message);
            }
        })
        .catch(error => {
            console.error('删除请求失败:', error);
            alert('删除失败，请稍后再试。');
        });
    }
}
// 页面加载完成后调用
document.addEventListener('DOMContentLoaded', loadMessages);
</script>
</body>
</html>

