<?php
session_start();
include 'db.php';


// Huỷ lịch hẹn
if (isset($_GET['cancel'])) {
    $id = $_GET['cancel'];
    $conn->query("UPDATE appointments SET status='Đã huỷ' WHERE id=$id");
}

// Thêm lịch hẹn mới
if (isset($_POST['add'])) {
    $doctor_id = $_POST['doctor_id'];
    $patient_id = $_POST['patient_id'];
    $appt_time = $_POST['appointment_time'];

    $sql = "INSERT INTO appointments (doctor_id, patient_id, appointment_time) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $doctor_id, $patient_id, $appt_time);
    $stmt->execute();
}

// Danh sách bác sĩ, bệnh nhân
$patients = $conn->query("SELECT * FROM patients");

// Hiển thị tất cả lịch hẹn
$appointments = $conn->query("
    SELECT a.*, p.name AS patient_name 
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    ORDER BY a.appointment_time DESC
");
?>
<style>
body {
    background: #f5f7fa;
    font-family: 'Segoe UI', Arial, sans-serif;
}
.appointment-container {
    width: 900px;
    margin: 40px auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 4px 24px rgba(56,142,60,0.08);
    padding: 36px 32px;
}
.appointment-container h2 {
    text-align: center;
    margin-bottom: 28px;
    color: #388e3c;
    font-size: 2rem;
    letter-spacing: 1px;
}
.appointment-container form {
    display: flex;
    gap: 18px;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    background: #e8f5e9;
    padding: 18px 14px;
    border-radius: 8px;
    box-shadow: 0 1px 6px rgba(56,142,60,0.04);
}
.appointment-container label {
    font-weight: 500;
    color: #388e3c;
    margin-right: 6px;
}
.appointment-container select,
.appointment-container input[type="datetime-local"] {
    padding: 9px;
    border: 1px solid #bdbdbd;
    border-radius: 6px;
    font-size: 15px;
    background: #f9fafb;
    min-width: 160px;
}
.appointment-container button {
    padding: 10px 22px;
    background: linear-gradient(90deg,#388e3c 60%,#43a047 100%);
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(56,142,60,0.09);
    transition: background 0.2s, box-shadow 0.2s;
    margin-left: 10px;
}
.appointment-container button:hover {
    background: #256029;
    box-shadow: 0 4px 16px rgba(56,142,60,0.13);
}
.appointment-container table {
    width: 100%;
    border-collapse: collapse;
    background: #f9fafb;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 8px rgba(56,142,60,0.05);
}
.appointment-container th, .appointment-container td {
    padding: 12px 8px;
    border: 1px solid #e0e0e0;
    text-align: center;
    font-size: 15px;
}
.appointment-container th {
    background: #e8f5e9;
    color: #388e3c;
    font-weight: 600;
}
.appointment-container tr:nth-child(even) {
    background: #f1f8e9;
}
.appointment-container .status {
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 16px;
    background: #fffde7;
    color: #fbc02d;
    display: inline-block;
}
.appointment-container .status.booked {
    background: #e3f0fc;
    color: #1976d2;
}
.appointment-container .status.cancelled {
    background: #ffebee;
    color: #d32f2f;
}
.appointment-container a.action {
    color: #d32f2f;
    text-decoration: none;
    font-weight: 500;
    padding: 6px 14px;
    border-radius: 6px;
    background: #ffebee;
    transition: background 0.2s;
}
.appointment-container a.action:hover {
    background: #ffcdd2;
    text-decoration: underline;
}
.appointment-container .icon {
    font-size: 18px;
    vertical-align: middle;
    margin-right: 4px;
}
</style>

<div class="appointment-container">
    <h2><span class="icon">📅</span> Quản lý lịch hẹn khám bệnh</h2>

    <!-- Form thêm lịch hẹn -->
    <form method="POST">
        <label for="doctor_id"><span class="icon">🩺</span>Bác sĩ:</label>
        <select name="doctor_id" id="doctor_id" required>
            <?php
            // Reset lại con trỏ để lặp lại
            $doctors->data_seek(0);
            while ($d = $doctors->fetch_assoc()): ?>
                <option value="<?= $d['id'] ?>"><?= $d['name'] ?> (<?= $d['specialty'] ?>)</option>
            <?php endwhile; ?>
        </select>

        <label for="patient_id"><span class="icon">👤</span>Bệnh nhân:</label>
        <select name="patient_id" id="patient_id" required>
            <?php
            $patients->data_seek(0);
            while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>"><?= $p['name'] ?> - <?= $p['patient_code'] ?></option>
            <?php endwhile; ?>
        </select>

        <label for="appointment_time"><span class="icon">⏰</span>Ngày giờ hẹn:</label>
        <input type="datetime-local" name="appointment_time" id="appointment_time" required>

        <button type="submit" name="add"><span class="icon">➕</span>Đặt lịch hẹn</button>
    </form>

    <!-- Bảng lịch hẹn -->
    <table>
        <tr>
            <th>🩺 Bác sĩ</th>
            <th>👤 Bệnh nhân</th>
            <th>⏰ Thời gian</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
        <?php while ($a = $appointments->fetch_assoc()): ?>
            <tr>
                <td><?= $a['doctor_name'] ?></td>
                <td><?= $a['patient_name'] ?></td>
                <td><?= date('d/m/Y H:i', strtotime($a['appointment_time'])) ?></td>
                <td>
                    <?php if ($a['status'] == 'Đã đặt'): ?>
                        <span class="status booked">Đã đặt</span>
                    <?php elseif ($a['status'] == 'Đã huỷ'): ?>
                        <span class="status cancelled">Đã huỷ</span>
                    <?php else: ?>
                        <span class="status"><?= $a['status'] ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($a['status'] == 'Đã đặt'): ?>
                        <a href="?page=appointment&cancel=<?= $a['id'] ?>" class="action" onclick="return confirm('Huỷ lịch hẹn này?')"><span class="icon">❌</span>Huỷ</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
