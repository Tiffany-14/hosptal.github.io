<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['patient_id']; // dùng để truy xuất thông tin điều trị
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang khách hàng</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f4f8; padding: 40px; }
        h1 { color: #1e3a8a; }
        .section { margin-bottom: 30px; }
        a.button {
            display: inline-block;
            padding: 10px 20px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-right: 10px;
        }
        a.button:hover { background: #2563eb; }
    </style>
</head>
<body>
    <h1>Chào mừng, <?php echo $_SESSION['username']; ?>!</h1>

    <div class="section">
        <h2>📅 Lịch hẹn</h2>
        <a href="book_appointment.php" class="button">Đặt lịch hẹn</a>
        <a href="cancel_appointment.php" class="button">Huỷ lịch hẹn</a>
    </div>

    <div class="section">
        <h2>💳 Thanh toán</h2>
        <a href="payment.php" class="button">Thanh toán dịch vụ</a>
    </div>

    <div class="section">
        <h2>🩺 Thông tin điều trị</h2>
        <a href="treatment_info.php?patient_id=<?php echo $patient_id; ?>" class="button">Xem hồ sơ điều trị</a>
    </div>
</body>
</html>
