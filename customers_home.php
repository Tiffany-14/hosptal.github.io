<?php
session_start();
if ($_SESSION['user']['role'] !== 'customer') {
    header("Location: index.php");
    exit;
}
?>

<h2>Chào mừng <?= $_SESSION['user']['username'] ?>!</h2>
<p>Thông tin cá nhân: <?= $_SESSION['user']['patient_id'] ?></p>

<ul>
    <li><a href="../controllers/AppointmentController.php?action=my">📅 Lịch hẹn của tôi</a></li>
    <li><a href="../controllers/TreatmentController.php?action=my">💊 Điều trị của tôi</a></li>
    <li><a href="../controllers/InvoiceController.php?action=my">💰 Hóa đơn của tôi</a></li>
</ul>