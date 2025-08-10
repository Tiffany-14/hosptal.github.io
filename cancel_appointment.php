<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}
$patient_id = $_SESSION['patient_id'];
// Kết nối database
$conn = new mysqli("localhost", "root", "", "hospital");
$result = $conn->query("SELECT * FROM appointments WHERE patient_id = $patient_id AND status = 'booked'");
?>
<h2>Huỷ lịch hẹn</h2>
<form method="post">
    <select name="appointment_id">
        <?php while($row = $result->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>">
                <?php echo $row['date'] . " - " . $row['doctor']; ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Huỷ</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['appointment_id'];
    $conn->query("UPDATE appointments SET status='cancelled' WHERE id=$id");
    echo "Đã huỷ lịch hẹn!";
}
?>