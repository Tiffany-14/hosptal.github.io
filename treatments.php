<?php
// Kết nối MySQL
$host = 'localhost';
$user = 'root';
$password = ''; // hoặc mật khẩu của bạn
$dbname = 'hospital';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý cập nhật tiến độ nếu có POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['patient_id'];
    $progress = $_POST['progress'];
    $nextDate = $_POST['nextDate'];

    $stmt = $conn->prepare("UPDATE patients SET progress = ?, next_appointment = ? WHERE id = ?");
    $stmt->bind_param("ssi", $progress, $nextDate, $id);
    $stmt->execute();
    $stmt->close();
}

// Lấy danh sách bệnh nhân
$result = $conn->query("SELECT * FROM patients");
$patients = [];
while ($row = $result->fetch_assoc()) {
    $patients[] = $row;
}
?>

<?php include 'includes/header.php'; ?>

<style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 20px;
      background-color: #f9f9f9;
      color: #333;
    }
    h2 {
      color: #64a8daff;
      border-bottom: 2px solid #64a8daff;
      padding-bottom: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: left;
    }
    th {
      background-color: #64a8daff;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f2f2f2;
    }
    .form-section {
      margin-top: 20px;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    label {
      font-weight: bold;
      margin-top: 10px;
      display: block;
    }
    select, textarea, input[type="date"], button {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    button {
      background-color: #64a8daff;
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    button:hover {
      background-color: #64a8daff;
    }
</style>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Kế hoạch điều trị</title>
</head>
<body>
<div class="main-content">
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="content-wrapper">
        <div class="patient-management">
            <h2>📋 Kế hoạch điều trị</h2>
            <table class="patient-table">
              <tr>
                <th>Bệnh nhân</th>
                <th>Tình trạng</th>
                <th>Lịch trình</th>
                <th>Tiến độ</th>
                <th>Lịch hẹn tiếp theo</th>
              </tr>
              <?php foreach ($patients as $p): ?>
              <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['treatment']) ?></td>
                <td><?= htmlspecialchars($p['schedule']) ?></td>
                <td><?= htmlspecialchars($p['progress']) ?></td>
                <td><?= htmlspecialchars($p['next_appointment']) ?></td>
              </tr>
              <?php endforeach; ?>
            </table>

            <h2>📝 Cập nhật tiến độ điều trị</h2>
            <div class="form-section">
                <form method="POST" onsubmit="return validateForm();">
                    <label for="patient_id">Chọn bệnh nhân:</label>
                    <select id="patient_id" name="patient_id">
                        <?php foreach ($patients as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select><br><br>

                    <label for="progress">Tiến độ điều trị:</label><br>
                    <textarea id="progress" name="progress" rows="4" cols="50"></textarea><br><br>

                    <label for="nextDate">Ngày hẹn tiếp theo:</label>
                    <input type="date" id="nextDate" name="nextDate"><br><br>

                    <button type="submit">Cập nhật</button>
                </form>
            </div>

            <script>
                function validateForm() {
                    const progress = document.getElementById('progress').value.trim();
                    const nextDate = document.getElementById('nextDate').value;

                    if (!progress || !nextDate) {
                        alert("Vui lòng nhập đầy đủ thông tin.");
                        return false;
                    }
                    return true;
                }
            </script>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>
