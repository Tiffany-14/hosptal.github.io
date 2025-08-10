<?php
session_start();
include 'db.php'; // tệp này chứa thông tin kết nối DB

// Thêm mới bệnh nhân
if (isset($_POST['add'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];

    $stmt = $conn->prepare("INSERT INTO patients (id, name, dob, gender) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id, $name, $dob, $gender);
    $stmt->execute();
    $stmt->close();
}

// Xoá bệnh nhân
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM patients WHERE id = '$id'");
}

// Lấy danh sách bệnh nhân
$result = $conn->query("SELECT * FROM patients");
?>

<h2>👩‍⚕️ Danh sách bệnh nhân</h2>
<table>
  <tr>
    <th>ID</th><th>Tên</th><th>Ngày sinh</th><th>Giới tính</th><th>Hành động</th>
  </tr>
  <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['name'] ?></td>
      <td><?= $row['dob'] ?></td>
      <td><?= $row['gender'] ?></td>
      <td>
        <a href="?delete=<?= $row['id'] ?>" onclick="return confirm('Xoá?')">❌ Xoá</a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<h3>➕ Thêm bệnh nhân mới</h3>
<form method="POST">
  ID: <input name="id" required><br>
  Tên: <input name="name" required><br>
  Ngày sinh: <input type="date" name="dob"><br>
  Giới tính: 
  <select name="gender">
    <option value="Nam">Nam</option>
    <option value="Nữ">Nữ</option>
  </select><br>
  <button type="submit" name="add">Thêm</button>
</form>
