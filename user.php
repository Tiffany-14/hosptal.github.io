<?php
session_start();
include("db.php");

// Thêm người dùng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (user_id, username, password, role) VALUES (?, ?, ?, ?)");
    $user_id = 'USR' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    $stmt->bind_param("ssss", $user_id, $username, $password, $role);
    $stmt->execute();
    $stmt->close();
}

// Xoá người dùng
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
}

// Lấy danh sách người dùng
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<style>
body {
    background: #f5f7fa;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.user-container {
    width: 850px;
    margin: 40px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.09);
    padding: 32px 28px;
}
.user-container h2 {
    text-align: center;
    margin-bottom: 24px;
    color: #388e3c;
    letter-spacing: 1px;
}
.user-container form {
    display: flex;
    gap: 14px;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    background: #e8f5e9;
    padding: 16px 12px;
    border-radius: 8px;
}
.user-container input, .user-container select {
    padding: 8px;
    border: 1px solid #bdbdbd;
    border-radius: 5px;
    font-size: 15px;
}
.user-container button {
    padding: 8px 18px;
    background: #388e3c;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.2s;
}
.user-container button:hover {
    background: #256029;
}
.user-container table {
    width: 100%;
    border-collapse: collapse;
    background: #f9fafb;
}
.user-container th, .user-container td {
    padding: 10px 8px;
    border: 1px solid #e0e0e0;
    text-align: center;
}
.user-container th {
    background: #e8f5e9;
    color: #388e3c;
}
.user-container tr:nth-child(even) {
    background: #f1f8e9;
}
.user-container a.action {
    color: #1976d2;
    text-decoration: none;
    font-weight: bold;
    margin: 0 6px;
}
.user-container a.action.delete {
    color: #d32f2f;
}
.user-container a.action:hover {
    text-decoration: underline;
}
.user-container .icon {
    font-size: 18px;
    vertical-align: middle;
    margin-right: 4px;
}
</style>

<div class="user-container">
    <h2><span class="icon">👥</span> Quản lý người dùng</h2>
    <!-- Form thêm người dùng -->
    <form method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <select name="role" required>
            <option value="">Vai trò</option>
            <option value="admin">Quản trị</option>
            <option value="doctor">Bác sĩ</option>
            <option value="nurse">Y tá</option>
        </select>
        <button type="submit" name="add"><span class="icon">➕</span>Thêm người dùng</button>
    </form>

    <!-- Bảng danh sách người dùng -->
    <table>
        <tr>
            <th>👤 Tên đăng nhập</th>
            <th>🔑 Vai trò</th>
            <th>Hành động</th>
        </tr>
        <?php while ($u = $users->fetch_assoc()): ?>
            <tr>
                <td><?= $u['username'] ?></td>
                <td><?= ucfirst($u['role']) ?></td>
                <td>
                    <a href="?edit=<?= $u['id'] ?>" class="action"><span class="icon">✏️</span>Sửa</a>
                    <a href="?delete=<?= $u['id'] ?>" class="action delete" onclick="return confirm('Xoá người dùng này?')"><span class="icon">❌</span>Xoá</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
